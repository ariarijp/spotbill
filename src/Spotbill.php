<?php

namespace Spotbill;

class Spotbill
{
    /**
     * @var int|null
     */
    private static $previousInodeNumber = null;

    /**
     * @var int
     */
    private static $retryCount = 0;

    /**
     * @var int
     */
    private static $sleepSeconds = 1;

    /**
     * @var int
     */
    private static $maxRetryCount = -1;

    /**
     * @return callable
     */
    private static function getDefaultInodeNumberChangedHandler()
    {
        return function ($fileName) {
            return fopen($fileName, 'r');
        };
    }

    /**
     * @return int
     */
    public static function getMaxRetryCount()
    {
        return self::$maxRetryCount;
    }

    /**
     * @param int $maxRetryCount
     */
    public static function setMaxRetryCount($maxRetryCount = -1)
    {
        self::$maxRetryCount = $maxRetryCount;
    }

    /**
     * @return int
     */
    public static function getSleepSeconds()
    {
        return self::$sleepSeconds;
    }

    /**
     * @param int $sleepSeconds
     */
    public static function setSleepSeconds($sleepSeconds = 1)
    {
        self::$sleepSeconds = $sleepSeconds;
    }

    /**
     * @param string $message
     */
    private static function handleError($message)
    {
        echo $message.PHP_EOL;
        exit();
    }

    /**
     * @param resource $fp
     * @param string   $fileName
     * @param callable $inodeNumberChangedHandler
     *
     * @return resource
     */
    private static function getFilePointer($fp, $fileName, $inodeNumberChangedHandler)
    {
        clearstatcache(true, $fileName);
        $currentInodeNumber = fileinode($fileName);
        if (!is_null(self::$previousInodeNumber) && self::$previousInodeNumber != $currentInodeNumber) {
            fclose($fp);

            $fp = $inodeNumberChangedHandler($fileName);
        }

        self::$previousInodeNumber = $currentInodeNumber;

        return $fp;
    }

    /**
     * @param string        $fileName
     * @param callable      $lineHandler
     * @param callable|null $inodeNumberChangedHandler
     */
    public static function tail($fileName, callable $lineHandler, callable $inodeNumberChangedHandler = null)
    {
        declare (ticks = 1);
        pcntl_signal(SIGINT, function () {
            self::handleError('Abort.');
        });

        if (!file_exists($fileName)) {
            self::handleError('No such file.');
        }

        if (is_null($inodeNumberChangedHandler)) {
            $inodeNumberChangedHandler = self::getDefaultInodeNumberChangedHandler();
        }

        $fp = fopen($fileName, 'r');

        while (true) {
            sleep(self::$sleepSeconds);

            if (!file_exists($fileName)) {
                if (self::$maxRetryCount != -1 && self::$retryCount >= self::$maxRetryCount) {
                    self::handleError('Max retry count exceeded.');
                }
                ++self::$retryCount;
                continue;
            }

            $fp = self::getFilePointer($fp, $fileName, $inodeNumberChangedHandler);
            while ($line = fgets($fp, 4096)) {
                $lineHandler($line);
            }

            fseek($fp, 0, SEEK_END);
        }
    }
}
