<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px">
    </a>
    <h1 align="center">Yii Mailer Library</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/mailer/v/stable.png)](https://packagist.org/packages/yiisoft/mailer)
[![Total Downloads](https://poser.pugx.org/yiisoft/mailer/downloads.png)](https://packagist.org/packages/yiisoft/mailer)
[![Build status](https://github.com/yiisoft/mailer/workflows/build/badge.svg)](https://github.com/yiisoft/mailer/actions?query=workflow%3Abuild)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yiisoft/mailer/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/mailer/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/yiisoft/mailer/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/yiisoft/mailer/?branch=master)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fmailer%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/mailer/master)
[![static analysis](https://github.com/yiisoft/mailer/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/mailer/actions?query=workflow%3A%22static+analysis%22)
[![type-coverage](https://shepherd.dev/github/yiisoft/mailer/coverage.svg)](https://shepherd.dev/github/yiisoft/mailer)


This package provides only the content composition functionality and basic interface.
Actual mail sending mechanism should be provided by the extension, because different projects may require its different
implementation, and it usually depends on the external services and libraries.

## General usage

In all implementations, the following code can be used when sending mail:

```php
/**
 * @var \Yiisoft\Mailer\MailerInterface $mailer
 */

$mailer->compose()
    ->withFrom('from@domain.com')
    ->withTo('to@domain.com')
    ->withSubject('Message subject')
    ->withTextBody('Plain text content')
    ->withHtmlBody('<b>HTML content</b>')
    ->send();
```

### Mailer implementations

- [Swift Mailer](https://github.com/yiisoft/mailer-swiftmailer)

### Unit testing

The package is tested with [PHPUnit](https://phpunit.de/). To run tests:

```shell
./vendor/bin/phpunit
```

### Mutation testing

The package tests are checked with [Infection](https://infection.github.io/) mutation framework. To run it:

```shell
./vendor/bin/infection
```

### Static analysis

The code is statically analyzed with [Psalm](https://psalm.dev/). To run static analysis:

```shell
./vendor/bin/psalm
```

### Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

### Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)

## License

The Yii Mailer Library is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).
