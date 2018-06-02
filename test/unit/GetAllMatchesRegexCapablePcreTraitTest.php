<?php

namespace Dhii\Regex\UnitTest;

use Dhii\Regex\GetAllMatchesRegexCapablePcreTrait as TestSubject;
use RuntimeException;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class GetAllMatchesRegexCapablePcreTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Regex\GetAllMatchesRegexCapablePcreTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a mock that uses traits.
     *
     * This is particularly useful for testing integration between multiple traits.
     *
     * @since [*next-version*]
     *
     * @param string[] $traitNames Names of the traits for the mock to use.
     *
     * @return MockBuilder The builder for a mock of an object that uses the traits.
     */
    public function mockTraits($traitNames = [])
    {
        $paddingClassName = uniqid('Traits');
        $definition = vsprintf('abstract class %1$s {%2$s}', [
            $paddingClassName,
            implode(
                ' ',
                array_map(
                    function ($v) {
                        return vsprintf('use %1$s;', [$v]);
                    },
                    $traitNames)),
        ]);
        var_dump($definition);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Runtime exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RuntimeException|MockObject The new exception.
     */
    public function createRuntimeException($message = '')
    {
        $mock = $this->getMockBuilder('RuntimeException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests that `_getAllMatchesRegex()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testGetAllMatchesSuccess()
    {
        $delim = '/';
        $match = uniqid('match');
        $pattern = vsprintf('%1$s%2$s%1$s', [$delim, preg_quote($match)]);
        $text = vsprintf('%1$s%2$s%3$s', [uniqid(), $match, uniqid()]);

        $subject = $this->createInstance(['_normalizeString']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(2))
            ->method('_normalizeString')
            ->withConsecutive([$pattern], [$text])
            ->will($this->returnArgument(0));

        $result = $_subject->_getAllMatchesRegex($pattern, $text);
        $this->assertEquals([[$match]], $result, 'Matches retrieved are incorrect');
    }

    /**
     * Tests that `_getAllMatchesRegex()` fails correctly when a regex error occurs.
     *
     * @since [*next-version*]
     */
    public function testGetAllMatchesFailureMatch()
    {
        $delim = '/';
        $pattern = vsprintf('%1$s%2$s%1$sabcdefg', [$delim, uniqid('match')]);
        $text = uniqid('text');
        $exception = $this->createRuntimeException('Regex error');

        $subject = $this->createInstance(['_normalizeString']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(2))
            ->method('_normalizeString')
            ->withConsecutive([$pattern], [$text])
            ->will($this->returnArgument(0));
        $subject->expects($this->exactly(1))
            ->method('_createRuntimeException')
            ->with(
                $this->isType('string'),
                $this->anything(),
                $this->anything()
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('RuntimeException');
        $_subject->_getAllMatchesRegex($pattern, $text);
    }
}
