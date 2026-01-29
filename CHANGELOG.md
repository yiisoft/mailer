# Yii Mailer Change Log

## 6.0.2 under development

- New #72: Add debug collector (@xepozz)
- Enh #118: Explicitly import classes, functions, and constants in "use" section (@mspirkov)
- Enh #118: Remove unnecessary files from Composer package (@mspirkov)

## 6.0.1 March 25, 2025

- Chg #114: Change PHP constraint in `composer.json` to `8.1 - 8.4` (@vjik)
- Bug #114: Explicitly mark nullable parameters (@vjik)

## 6.0.0 October 18, 2024

- New #101: Add `NullMailer` and `StubMailer` (@vjik)
- New #104: Add `FileMailer` configuration (@vjik)
- New #109: Add `HtmlToTextBodyConverter` (@vjik)
- New #84: Add `MessageInterface` methods: `getAttachments()`,  `getEmbeddings()`, `getHeaders()`,
  `withAddedAttachments()`, `withAddedEmbeddings()` (@vjik)
- New #84: Add `Message` class that implements `MessageInterface` (@vjik)
- Chg #100: Remove `MessageFactoryInterface` and `MessageFactory`, add `MessageSettings` instead it (@vjik)
- Chg #101: Remove `MessageBodyRenderer` and `MessageBodyTemplate` classes (@vjik)
- Chg #101: Remove `yiisoft/view` dependency (@vjik)
- Chg #101: Remove `MessageInterface` methods `compose()`, `withTemplate()` and `withLocale()` (@vjik)
- Chg #102, #108: Rename `Mailer` to `BaseMailer`, remove `beforeSend()` and `afterSend()` methods, finalize methods,
  and mark dependencies as read only (@vjik)
- Chg #102: Remove `getMessage()` method from `AfterSend` and `BeforeSend` classes, make `$message` property public
 read only instead it (@vjik)
- Chg #105: Change result type of `MessageInterface::with*` methods from `self` to `static` (@vjik)
- Chg #108: Remove implementation of `StoppableEventInterface` from `BeforeSend` and add `$preventSendingMessage`
  property to it (@vjik)
- Chg #108: Remove `beforeSend()` and `afterSend()` messages from `BaseMailer` (@vjik)
- Chg #84: Rename `MessageInterface` methods: `withEmbedded()` to `withEmbeddings()`, `withAttached()`
  to `withAttachments()` and allow passing several files to them (@vjik)
- Chg #94: Remove `getError()` and `withError()` methods from `MessageInterface` (@vjik)
- Chg #94: Change result of `MailerInterface::sendMultiple()` to `SendResults` object (@vjik)
- Chg #95: Use new `Priority` enumeration instead of integer value for define priority in message (@vjik)
- Chg #96: Change order of constructor parameters in `Mailer` and `FailMailer` (@vjik)
- Enh #83, #103: Make `psr/event-dispatcher` dependency optional (@vjik)
- Enh #103: Replace `RuntimeException` to `LogicException` when file name callback of `FileMailer` returns non-string
  value and improve an exception message (@vjik)

## 5.1.0 July 02, 2024

- New #82: Allow setting default "from" value in `MessageFactory` (@vjik)
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
