<?php

declare(strict_types=1);

namespace App\Tests\_helpers;

use PHPUnit\Framework\Assert;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\Exception\AccessException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

class ResponseAsserter extends Assert
{
    private static $instance;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    public static function getInstance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Asserts the array of property names are in the JSON response.
     *
     * @expectedException
     */
    public function assertResponsePropertiesExist(Response $response, array $expectedProperties): void
    {
        foreach ($expectedProperties as $propertyPath) {
            // this will blow up if the property doesn't exist
            $this->readResponseProperty($response, $propertyPath);
        }
    }

    /**
     * Asserts the array of property names are in the JSON response.
     *
     * @expectedException
     */
    public function assertResponsePropertiesEquals(Response $response, array $expectedPropertiesWithValue): void
    {
        foreach ($expectedPropertiesWithValue as $propertyPath => $propertyValue) {
            $this->assertResponsePropertyEquals($response, $propertyPath, $propertyValue);
        }
    }

    /**
     * Asserts the response JSON property equals the given value.
     *
     * @expectedException
     *
     * @param string $propertyPath  e.g. data[0].category.name
     * @param mixed  $expectedValue
     */
    public function assertResponsePropertyEquals(Response $response, string $propertyPath, $expectedValue): void
    {
        $actual = $this->readResponseProperty($response, $propertyPath);
        self::assertEquals(
            $expectedValue,
            $actual,
            sprintf(
                'Property "%s": Expected "%s" but response was "%s"',
                $propertyPath,
                \is_array($expectedValue) ? var_export($expectedValue, true) : $expectedValue,
                var_export($actual, true)
            )
        );
    }

    /**
     * @expectedException
     */
    public function assertStatusEquals(int $expectedStatus, Response $response): void
    {
        self::assertEquals($expectedStatus, $response->getStatusCode(), $response->getContent());
    }

    /**
     * @expectedException
     */
    public function assertResponsePropertyCount(Response $response, string $propertyPath, int $expectedValue): void
    {
        self::assertCount($expectedValue, $this->readResponseProperty($response, $propertyPath));
    }

    /**
     * Reads a JSON response property and returns the value.
     *
     * This will explode if the value does not exist
     *
     * @expectedException
     *
     * @param string $propertyPath e.g. games[0].category.id
     *
     * @return mixed
     */
    public function readResponseProperty(Response $response, string $propertyPath)
    {
        if (null === $this->accessor) {
            $this->accessor = PropertyAccess::createPropertyAccessor();
        }
        $data = json_decode((string) $response->getContent());
        if (null === $data) {
            throw new \Exception(sprintf('Cannot read property "%s" - the response is invalid', $propertyPath));
        }
        try {
            return $this->accessor->getValue($data, $propertyPath);
        } catch (AccessException $e) {
            // it could be a stdClass or an array of stdClass
            $values = \is_array($data) ? $data : get_object_vars($data);
            throw new AccessException(sprintf('Error reading property "%s" from available keys (%s) of object(%s)', $propertyPath, implode(', ', array_keys($values)), var_export($values, true)), 0, $e);
        }
    }
}
