<?php

/**
 * This file is part of the Zephir.
 *
 * (c) Phalcon Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Zephir\Exception;

use Exception;
use Throwable;

use function is_array;

/**
 * Exceptions generated by parsing actions
 */
class ParseException extends RuntimeException
{
    /**
     * ParseException constructor.
     *
     * @param string                   $message  the Exception message to throw [optional]
     * @param array|null               $extra    extra info [optional]
     * @param int                      $code     the Exception code [optional]
     * @param Exception|Throwable|null $previous the previous throwable used for the exception chaining [optional]
     */
    public function __construct(
        string $message = '',
        ?array $extra = null,
        int $code = 0,
        Exception | Throwable $previous = null
    ) {
        if (is_array($extra) && isset($extra['file'])) {
            $message .= ' in ' . $extra['file'] . ' on line ' . $extra['line'];
        }

        $this->extra = $extra;

        parent::__construct($message, $code, $previous);
    }
}
