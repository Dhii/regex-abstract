<?php

namespace Dhii\Regex;

use Exception as RootException;
use InvalidArgumentException;
use RuntimeException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Functionality for RegEx matching that retrieves all matches.
 *
 * If a custom error handler exists, and results in `preg_match_all()` throwing an exception, then the exception
 * thrown by `_getAllMatchesRegex()` will contain that error text, which is more detailed. If no such handler is
 * in place, the exception message would be much more generic.
 *
 * @since [*next-version*]
 */
trait GetAllMatchesRegexCapablePcreTrait
{
    /**
     * Retrieves all matches that correspond to a RegEx pattern from a string.
     *
     * @since [*next-version*]
     * @see preg_match_all()
     *
     * @param string|Stringable $pattern The RegEx pattern to use for matching.
     * @param string|Stringable $subject The subject that will be searched for matches.
     *
     * @throw InvalidArgumentException If the pattern or the subject are of the wrong type.
     * @throw RuntimeException If problem matching.
     *
     * @return array The array of matches. Format is same as the matches produced by `preg_match_all()`.
     */
    protected function _getAllMatchesRegex($pattern, $subject)
    {
        $matches = [];
        $pattern = $this->_normalizeString($pattern);
        $subject = $this->_normalizeString($subject);

        try {
            $result = @preg_match_all($pattern, $subject, $matches);
        } catch (RootException $e) {
            // This will happen if a custom error handler is set and throws exceptions for regex errors
            throw $this->_createRuntimeException($this->__($e->getMessage()), null, $e);
        }

        if ($result === false) {
            $errorCode = preg_last_error();
            throw $this->_createRuntimeException($this->__('RegEx error code "%1$s"', [$errorCode]));
        }

        return $matches;
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

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);

    /**
     * Creates a new Runtime exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|int|float|bool|null $message  The message, if any.
     * @param int|float|string|Stringable|null      $code     The numeric error code, if any.
     * @param RootException|null                    $previous The inner exception, if any.
     *
     * @return RuntimeException The new exception.
     */
    abstract protected function _createRuntimeException($message = null, $code = null, $previous = null);
}
