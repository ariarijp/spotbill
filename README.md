# Spotbill

[![Circle CI](https://circleci.com/gh/ariarijp/spotbill.svg?style=svg)](https://circleci.com/gh/ariarijp/spotbill)

`tail -f` for PHP.

## Installation

Add these lines to your `composer.json`.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/ariarijp/spotbill.git"
    }
],
"require": {
    "ariarijp/spotbill": "dev-master"
}
```

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
