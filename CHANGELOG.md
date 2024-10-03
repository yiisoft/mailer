# Yii Mailer Library Change Log

## 6.0.0 under development

- New #84: Add `MessageInterface` methods: `getAttachments()`,  `getEmbeddings()`, `getHeaders()`,
  `withAddedAttachments()`, `withAddedEmbeddings()` (@vjik)
- Chg #84: Rename `MessageInterface` methods: `withEmbedded()` to `withEmbeddings()`, `withAttached()`
  to `withAttachments()` and allow passing several files to them (@vjik)
- New #84: Add `Message` class that implements `MessageInterface` (@vjik)
- Chg #94: Remove `getError()` and `withError()` methods from `MessageInterface` (@vjik)
- Chg #94: Change result of `MailerInterface::sendMultiple()` to `SendResults` object (@vjik)
- Chg #95: Use new `Priority` enumeration instead of integer value for define priority in message (@vjik)
- Enh #83: Make `psr/event-dispatcher` dependency optional (@vjik)
- Chg #96: Change order of constructor parameters in `Mailer` and `FailMailer` (@vjik)
- Chg #98: Make `MessageBodyTemplate` simple DTO with public readonly properties (@vjik) 

## 5.1.0 July 02, 2024

- New #82: Allow to set default "from" value in `MessageFactory` (@vjik)
- Chg #85: Raise minimal PHP version to `^8.1` (@vjik)
- Chg #89: Raise required `yiisoft/view` version to `^10.0` (@vjik)

## 5.0.1 February 17, 2023

- Enh #63: Add support of `yiisoft/view` of version `^8.0` (@vjik)

## 5.0.0 December 28, 2022

- Chg #52: In `MessageInterface` methods move a type hints from annotation to signature (@vjik)
- Chg #60: Raise minimal PHP version to `^8.0` (@vjik)
- Enh #56: Add support of `yiisoft/view` of version `^7.0` (@vjik)

## 4.0.0 July 23, 2022

- New #44: Add immutable method `MailerInterface::withLocale()` that set locale (@thenotsoft)

## 3.0.3 February 04, 2022

- Chg #43: Update the `yiisoft/view` dependency, added `^5.0` (@thenotsoft)

## 3.0.2 October 26, 2021

- Chg #40: Update the `yiisoft/view` dependency to `^4.0` (@vjik)

## 3.0.1 September 18, 2021

- Chg: Update `yiisoft/view` dependency to `^3.0` (@samdark)

## 3.0.0 August 25, 2021

- New #39: Add methods to `Yiisoft\Mailer\MessageInterface`: `getDate()`, `withDate()`, `getPriority()`,
  `withPriority()`, `getReturnPath()`, `withReturnPath()`, `getSender()`, `withSender()` (@devanych)

## 2.0.0 August 24, 2021

- Chg: Use `yiisoft/view` `^2.0` (@samdark)

## 1.0.0 July 05, 2021

Initial release.
