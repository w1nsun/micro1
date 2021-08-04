<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\ResponseAsserter;
use App\UI\Http\Enum\ResponseStatusEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateCategoryActionTest extends WebTestCase
{
    use DbPurger;

    public function testCreateSuccessfully(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'category_id' => 1,
            'name' => 'TestName',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [
                'EUR' => 'EUR',
                'USD' => 'USD',
            ],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);
        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $asserter = ResponseAsserter::getInstance();
        $asserter->assertResponsePropertyEquals($response, 'status', ResponseStatusEnum::SUCCESS);
        $asserter->assertResponsePropertyEquals($response, 'data.category_id', 1);
        $asserter->assertResponsePropertyEquals($response, 'data.name', 'TestName');
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.extra_field', 'some_value_for_extra_field');
        $asserter->assertResponsePropertyEquals($response, 'data.currencies.EUR', 'EUR');
        $asserter->assertResponsePropertyEquals($response, 'data.currencies.USD', 'USD');
    }

    public function testCreateDuplicate(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'category_id' => 1,
            'name' => 'TestName',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);
        $client->restart();
        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testCreateWithValidationErrors(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'category_id' => 1,
            'name' => 't',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);

        $response = $client->getResponse();
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'errors.name[0]', 'This value is too short. It should have 2 characters or more.');
        $asserter->assertResponsePropertyEquals($response, 'errors.currencies[0]', 'This value should not be blank.');
    }

    public function testCreateWithEmptyCurrencyValidationErrors(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'category_id' => 1,
            'name' => 'Test',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [''],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);

        $response = $client->getResponse();
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'errors.currencies[0][0]', 'This value should not be blank.');
    }

    public function testCreateWithCurrencyValidationErrors(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'category_id' => 1,
            'name' => 'Test',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => ['hryvna'],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('POST', '/api/category', [], [], [], $payload);

        $response = $client->getResponse();
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'errors.currencies[0][0]', 'This value is not a valid currency.');
    }
}
