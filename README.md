<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://yiisoft.github.io/docs/images/yii_logo.svg" height="100px" alt="Yii">
    </a>
    <h1 align="center">Yii Mailer Library</h1>
    <br>
</p>

[![Latest Stable Version](https://poser.pugx.org/yiisoft/mailer/v)](https://packagist.org/packages/yiisoft/mailer)
[![Total Downloads](https://poser.pugx.org/yiisoft/mailer/downloads)](https://packagist.org/packages/yiisoft/mailer)
[![Build status](https://github.com/yiisoft/mailer/actions/workflows/build.yml/badge.svg)](https://github.com/yiisoft/mailer/actions/workflows/build.yml)
[![Code Coverage](https://codecov.io/gh/yiisoft/mailer/branch/master/graph/badge.svg)](https://codecov.io/gh/yiisoft/mailer)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyiisoft%2Fmailer%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/yiisoft/mailer/master)
[![static analysis](https://github.com/yiisoft/mailer/workflows/static%20analysis/badge.svg)](https://github.com/yiisoft/mailer/actions?query=workflow%3A%22static+analysis%22)
[![psalm-level](https://shepherd.dev/github/yiisoft/mailer/level.svg)](https://shepherd.dev/github/yiisoft/mailer)
[![type-coverage](https://shepherd.dev/github/yiisoft/mailer/coverage.svg)](https://shepherd.dev/github/yiisoft/mailer)

The package provides the content composition functionality, and a basic interface for sending emails.
Actual mail sending is provided by separate interchangeable packages.

Out of the box the package profiles a file mailer that, instead of actually sending an email, writes its
contents into a file. There are also [Swift Mailer](https://github.com/yiisoft/mailer-swiftmailer) and
[Symfony Mailer](https://github.com/yiisoft/mailer-symfony) based official drivers available as a
separate packages that actually can send emails.

## Requirements

- PHP 8.1 or higher.

## Installation

The package could be installed with [Composer](https://getcomposer.org):

```shell
composer require yiisoft/mailer
```

## General usage

The following code can be used to send an email:

```php
/**
 * @var \Yiisoft\Mailer\MailerInterface $mailer
 */

$message = (new \Yiisoft\Mailer\Message())
    ->withFrom('from@domain.com')
    ->withTo('to@domain.com')
    ->withSubject('Message subject')
    ->withTextBody('Plain text content')
    ->withHtmlBody('<b>HTML content</b>');
    
$mailer->send($message);
```

### Mailer implementations

- [Symfony Mailer](https://github.com/yiisoft/mailer-symfony)

## Documentation

- [Yii guide to mailing](https://github.com/yiisoft/docs/blob/master/guide/en/tutorial/mailing.md)
- [Internals](docs/internals.md)

If you need help or have a question, the [Yii Forum](https://forum.yiiframework.com/c/yii-3-0/63) is a good place for that.
You may also check out other [Yii Community Resources](https://www.yiiframework.com/community).

## License

The Yii Mailer Library is free software. It is released under the terms of the BSD License.
Please see [`LICENSE`](./LICENSE.md) for more information.

Maintained by [Yii Software](https://www.yiiframework.com/).

## Support the project

[![Open Collective](https://img.shields.io/badge/Open%20Collective-sponsor-7eadf1?logo=open%20collective&logoColor=7eadf1&labelColor=555555)](https://opencollective.com/yiisoft)

## Follow updates

[![Official website](https://img.shields.io/badge/Powered_by-Yii_Framework-green.svg?style=flat)](https://www.yiiframework.com/)
[![Twitter](https://img.shields.io/badge/twitter-follow-1DA1F2?logo=twitter&logoColor=1DA1F2&labelColor=555555?style=flat)](https://twitter.com/yiiframework)
[![Telegram](https://img.shields.io/badge/telegram-join-1DA1F2?style=flat&logo=telegram)](https://t.me/yii3en)
[![Facebook](https://img.shields.io/badge/facebook-join-1DA1F2?style=flat&logo=facebook&logoColor=ffffff)](https://www.facebook.com/groups/yiitalk)
[![Slack](https://img.shields.io/badge/slack-join-1DA1F2?style=flat&logo=slack)](https://yiiframework.com/go/slack)
