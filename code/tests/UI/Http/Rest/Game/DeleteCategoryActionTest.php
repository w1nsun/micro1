<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\GameFixtures;
use App\Tests\_helpers\CategoryFixtures;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DeleteCategoryActionTest extends WebTestCase
{
    use DbPurger;

    public function testDelete(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);
        $gameFixtures = new GameFixtures($em);
        $gameFixtures->createGame('game_id_1', 1);
        $gameFixtures->createGame('game_id_2', 1);

        $client->xmlHttpRequest('DELETE', '/api/category/1');

        self::assertResponseIsSuccessful();
    }

    public function testDeleteNotExists(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class]);

        $client->xmlHttpRequest('DELETE', '/api/category/1');

        self::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
