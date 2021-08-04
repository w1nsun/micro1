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

class GetGameActionTest extends WebTestCase
{
    use DbPurger;

    public function testGetGameSuccessfully(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);

        $gameFixtures = new GameFixtures($em);
        $game1 = $gameFixtures->createGame('1', 1);
        $platformGame1 = (new Platform())
                ->setExtraData(['platform_extra_field' => 'platform_extra_field_value'])
                ->setHasDemo(true)
                ->setEnabled(true)
                ->setCategoryGameId('category_game_id_1');
        $game1->setDesktop($platformGame1);
        $game1->setExtraData(['game_extra_field' => 'game_extra_field_value']);

        $em->flush();
        $em->clear();

        $client->xmlHttpRequest('GET', '/api/game/1');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'data.game_id', '1');
        $asserter->assertResponsePropertyEquals($response, 'data.game_extra_field', 'game_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.platform.desktop.platform_extra_field', 'platform_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.platform.desktop.category_game_id', 'category_game_id_1');
        $asserter->assertResponsePropertyEquals($response, 'data.platform.desktop.enabled', true);
        $asserter->assertResponsePropertyEquals($response, 'data.platform.desktop.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.category_id', 1);
    }

    public function testGetGameNotFound(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        $client->xmlHttpRequest('GET', '/api/game/1');
        $response = $client->getResponse();

        self::assertSame(404, $response->getStatusCode());
    }
}
