<?php

declare(strict_types=1);

namespace Yiisoft\Mailer\ViewMailer;

use LogicException;

/**
 * Stores the path to the view file directory and the layout view names.
 */
final class MessageBodyTemplate
{
    /**
     * @param string $viewPath The directory containing view files for composing mail messages.
     * @param string|null $htmlLayout The HTML layout view name. It is the relative to
     * {@see MessageBodyTemplate::$viewPath} path to file with layout used to render HTML mail body,
     * e.g., 'layouts/html'. If the value is `null`, no layout will be applied.
     * @param string|null $textLayout The text layout view name. It is the relative to
     * {@see MessageBodyTemplate::$viewPath} path to file with layout used to render text mail body,
     * e.g., 'layouts/text'. If the value is `null`, no layout will be applied.
     *
     * @psalm-param non-empty-string $viewPath
     * @psalm-param non-empty-string|null $htmlLayout
     * @psalm-param non-empty-string|null $textLayout
     *
     * @psalm-suppress TypeDoesNotContainType
     */
    public function __construct(
        public readonly string $viewPath,
        public readonly string|null $htmlLayout = null,
        public readonly string|null $textLayout = null,
    ) {
        if ($viewPath === '') {
            throw new LogicException('View path must be non-empty string.');
        }
        if ($htmlLayout === '') {
            throw new LogicException('The HTML layout view name must be non-empty string or null.');
        }
        if ($textLayout === '') {
            throw new LogicException('The text layout view name must be non-empty string or null.');
        }
    }
}
