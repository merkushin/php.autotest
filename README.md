[![Build Status](https://travis-ci.org/merkushin/php.autotest.png?branch=master)](https://travis-ci.org/merkushin/php.autotest) Autotest for PHP
===

Requirements
---

* PHP 5.3+
* PHPUnit
* Symfony/Console

Installation
---

Download and unpack php.autotest.

Download Composer (http://getcomposer.org) and run:

```
composer.phar install
```

Composer installs Console component


Optionally link bin/autotest.php to your /usr/bin like this:

```
ln bin/autotest.php /usr/bin/autotest
```

Usage
---

Run bin/autotest.php with optional params from the root of your application:

* ```cmd``` — path to phpunit (optional, default value: phpunit)
* ```src_path``` — path to your source code (optional, default value: src)
* ```tests_path``` — path to your tests (optional, default value: tests)
* ```suffix``` — suffix of the test namespace (optional, default value: Test)
* ```timeout``` — time between comparing files (optional, default value: 1 second)

Example (assuming file is linked to bin directory):

```
autotest --cmd=/usr/local/zend/bin/phpunit --src_path=source --tests_path=MyTests --timeout=60
```