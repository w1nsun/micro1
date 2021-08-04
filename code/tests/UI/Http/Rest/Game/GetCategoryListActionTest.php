<?php

declare(strict_types=1);

namespace UI\Http\Rest\Game;

use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\CategoryFixtures;
use App\Tests\_helpers\ResponseAsserter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCategoryListActionTest extends WebTestCase
{
    use DbPurger;

    public function testGetFirstPage(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        $category1 = (new CategoryFixtures($em))->createCategory(1);
        $category1->setName('Category1');

        $category2 = (new CategoryFixtures($em))->createCategory(2);
        $category2->setName('Category2');

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/category');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'meta.total', 2);

        $asserter->assertResponsePropertyEquals($response, 'data[0].category_id', 1);
        $asserter->assertResponsePropertyEquals($response, 'data[0].name', 'Category1');
        $asserter->assertResponsePropertyEquals($response, 'data[0].currencies.EUR', 'EUR');
        $asserter->assertResponsePropertyEquals($response, 'data[0].currencies.USD', 'USD');
    }

    public function testGetSecondPage(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        $category1 = (new CategoryFixtures($em))->createCategory(1);
        $category1->setName('Category1');

        $category2 = (new CategoryFixtures($em))->createCategory(2);
        $category2->setName('Category2');
        $category2->setCurrencies(['UAH' => 'UAH', 'RUB' => 'RUB']);

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/category?offset=1&limit=50');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'meta.total', 2);

        $asserter->assertResponsePropertyEquals($response, 'data[0].category_id', 2);
        $asserter->assertResponsePropertyEquals($response, 'data[0].name', 'Category2');
        $asserter->assertResponsePropertyEquals($response, 'data[0].currencies.UAH', 'UAH');
        $asserter->assertResponsePropertyEquals($response, 'data[0].currencies.RUB', 'RUB');
    }
}
