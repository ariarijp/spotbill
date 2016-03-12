# Spotbill

`tail` for PHP.

## Usage

```php
<?php
require_once __DIR__.'/vendor/autoload.php';

\Spotbill\Spotbill::setMaxRetryCount(10);
\Spotbill\Spotbill::setSleepSeconds(5);
\Spotbill\Spotbill::tail('/var/log/apache2/access.log', function ($line) {
    echo $line;
});
```

## License

MIT

## Author

[ariarijp](https://github.com/ariarijp)
