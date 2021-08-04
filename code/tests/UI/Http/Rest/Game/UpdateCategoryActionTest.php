<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\CategoryFixtures;
use App\Tests\_helpers\ResponseAsserter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UpdateCategoryActionTest extends WebTestCase
{
    use DbPurger;

    public function testUpdateSuccessfully(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory();

        $payload = json_encode([
            'name' => 'TestName',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [
                'UAH' => 'UAH',
            ],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('PUT', '/api/category/1', [], [], [], $payload);
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertStatusEquals(Response::HTTP_OK, $response);

        $asserter->assertResponsePropertyEquals($response, 'data.category_id', 1);
        $asserter->assertResponsePropertyEquals($response, 'data.name', 'TestName');
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.extra_field', 'some_value_for_extra_field');
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.game_session.ttl', 120);
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.game_session.db_log', false);
        $asserter->assertResponsePropertyEquals($response, 'data.currencies.UAH', 'UAH');
    }

    public function testUpdateNotExists(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $payload = json_encode([
            'name' => 'TestName',
            'configurator' => [
                'extra_field' => 'some_value_for_extra_field',
            ],
            'currencies' => [
                'USD' => 'USD',
            ],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('PUT', '/api/category/1', [], [], [], $payload);

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
