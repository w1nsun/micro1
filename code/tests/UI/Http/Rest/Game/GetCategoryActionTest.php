<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\DTO\ConfiguratorDTO;
use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\CategoryFixtures;
use App\Tests\_helpers\ResponseAsserter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCategoryActionTest extends WebTestCase
{
    use DbPurger;

    public function testGetSuccessfully(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        $category = (new CategoryFixtures($em))->createCategory(1);
        $configuratorDTO = new ConfiguratorDTO([
            'extra_field' => 'some_value_for_extra_field',
        ]);
        $category->setConfigurator($configuratorDTO);
        $category->setCurrencies(['EUR' => 'EUR', 'USD' => 'USD']);

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/category/1');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();
        $asserter->assertResponsePropertyEquals($response, 'data.category_id', 1);
        $asserter->assertResponsePropertyEquals($response, 'data.name', 'TestName');
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.extra_field', 'some_value_for_extra_field');
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.game_session.ttl', 120);
        $asserter->assertResponsePropertyEquals($response, 'data.configurator.game_session.db_log', false);
        $asserter->assertResponsePropertyEquals($response, 'data.currencies.EUR', 'EUR');
        $asserter->assertResponsePropertyEquals($response, 'data.currencies.USD', 'USD');
    }
}
