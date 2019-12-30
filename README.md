# RQuadling/Enumeration

[![Build Status](https://img.shields.io/travis/rquadling/enumeration.svg?style=for-the-badge&logo=travis)](https://travis-ci.org/rquadling/enumeration)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/rquadling/enumeration.svg?style=for-the-badge&logo=scrutinizer)](https://scrutinizer-ci.com/g/rquadling/enumeration/)
[![GitHub issues](https://img.shields.io/github/issues/rquadling/enumeration.svg?style=for-the-badge&logo=github)](https://github.com/rquadling/enumeration/issues)

[![PHP Version](https://img.shields.io/packagist/php-v/rquadling/enumeration.svg?style=for-the-badge)](https://github.com/rquadling/enumeration)
[![Stable Version](https://img.shields.io/packagist/v/rquadling/enumeration.svg?style=for-the-badge&label=Latest)](https://packagist.org/packages/rquadling/enumeration)

[![Total Downloads](https://img.shields.io/packagist/dt/rquadling/enumeration.svg?style=for-the-badge&label=Total+downloads)](https://packagist.org/packages/rquadling/enumeration)
[![Monthly Downloads](https://img.shields.io/packagist/dm/rquadling/enumeration.svg?style=for-the-badge&label=Monthly+downloads)](https://packagist.org/packages/rquadling/enumeration)
[![Daily Downloads](https://img.shields.io/packagist/dd/rquadling/enumeration.svg?style=for-the-badge&label=Daily+downloads)](https://packagist.org/packages/rquadling/enumeration)

Extension to Eloquent/Enumeration for use within RQuadling's projects.

## Installation

Using Composer:

```sh
composer require rquadling/enumeration
```

## PHPStan rules

A [PHPStan](https://github.com/phpstan/phpstan) rule governing the enumeration class is available.

The rules are:
1. Attempting to extend `\Eloquent\Enumeration\AbstractEnumeration` rather than `\RQuadling\Enumeration\AbstractEnumeration`.
2. Multiple names exist for the the values in the enumeration.
3. Missing `@method static` docblocks.

If you also install [phpstan/extension-installer](https://github.com/phpstan/extension-installer) then you're all set!

### Manual installation

If you don't want to use `phpstan/extension-installer`, include `rules.neon` in your project's PHPStan config:

```
includes:
    - vendor/rquadling/enumeration/rules.neon
```
