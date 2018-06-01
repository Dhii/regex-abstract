<?php

namespace Dhii\Regex;

use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;

/**
 * Functionality for quoting strings according to PCRE.
 *
 * @since [*next-version*]
 */
trait QuoteRegexCapablePcreTrait
{
    /**
     * Escapes special characters in a string such that it is interpreted literally by a PCRE parser.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable      $string    The string to quote.
     * @param string|Stringable|null $delimiter The delimiter that will be used in the expression, if any.
     *                                          If specified, this delimiter will be quoted too.
     *
     * @throws InvalidArgumentException If the string or the delimiter are invalid.
     *
     * @return string The quoted string.
     */
    protected function _quoteRegex($string, $delimiter = null)
    {
        $string    = $this->_normalizeString($string);
        $delimiter = is_null($delimiter) ? null : $this->_normalizeString($delimiter);

        return preg_quote($string, $delimiter);
    }

    /**
     * Normalizes a value to its string representation.
     *
     * The values that can be normalized are any scalar values, as well as
     * {@see StringableInterface).
     *
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $subject The value to normalize to string.
     *
     * @throws InvalidArgumentException If the value cannot be normalized.
     *
     * @return string The string that resulted from normalization.
     */
    abstract protected function _normalizeString($subject);
}
