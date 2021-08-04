<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\Platform;
use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\GameFixtures;
use App\Tests\_helpers\CategoryFixtures;
use App\Tests\_helpers\ResponseAsserter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetCategoryGamesActionTest extends WebTestCase
{
    use DbPurger;

    public function testGetFirstPage(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);

        $gameFixtures = new GameFixtures($em);
        $game1 = $gameFixtures->createGame('game_id_1', 1);
        $platformGame1 = (new Platform())
                ->setExtraData(['platform_extra_field' => 'platform_extra_field_value'])
                ->setHasDemo(true)
                ->setEnabled(true)
                ->setCategoryGameId('category_game_id_1');
        $game1->setDesktop($platformGame1);
        $game1->setExtraData(['game_extra_field' => 'game_extra_field_value']);

        $game2 = $gameFixtures->createGame('game_id_2', 1);
        $platformGame2 = (new Platform())
            ->setExtraData(['platform_extra_field' => 'platform_extra_field_value'])
            ->setHasDemo(true)
            ->setEnabled(true)
            ->setCategoryGameId('category_game_id_2');
        $game2->setMobile($platformGame2);
        $game2->setExtraData(['game_extra_field' => 'game_extra_field_value']);

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/category-games/1');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'meta.total', 2);

        $asserter->assertResponsePropertyEquals($response, 'data.games[0].game_id', 'game_id_1');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].game_extra_field', 'game_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.platform_extra_field', 'platform_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.category_game_id', 'category_game_id_1');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.enabled', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.has_demo', true);

        $asserter->assertResponsePropertyEquals($response, 'data.games[1].game_id', 'game_id_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].game_extra_field', 'game_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.platform_extra_field', 'platform_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.category_game_id', 'category_game_id_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.enabled', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.has_demo', true);
    }

    public function testGetSecondPage(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);

        $gameFixtures = new GameFixtures($em);
        $game1 = $gameFixtures->createGame('game_id_1', 1);
        $platformGame1 = (new Platform())
            ->setExtraData(['platform_extra_field' => 'platform_extra_field_value'])
            ->setHasDemo(true)
            ->setEnabled(true)
            ->setCategoryGameId('category_game_id_1');
        $game1->setDesktop($platformGame1);
        $game1->setExtraData(['game_extra_field' => 'game_extra_field_value']);

        $game2 = $gameFixtures->createGame('game_id_2', 1);
        $platformGame2 = (new Platform())
            ->setExtraData(['platform_extra_field' => 'platform_extra_field_value'])
            ->setHasDemo(true)
            ->setEnabled(true)
            ->setCategoryGameId('category_game_id_2');
        $game2->setMobile($platformGame2);
        $game2->setExtraData(['game_extra_field' => 'game_extra_field_value']);

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/category-games/1?offset=1&limit=50');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'meta.total', 2);

        $asserter->assertResponsePropertyEquals($response, 'data.games[0].game_id', 'game_id_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].game_extra_field', 'game_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.mobile.platform_extra_field', 'platform_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.mobile.category_game_id', 'category_game_id_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.mobile.enabled', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.mobile.has_demo', true);
    }
}
