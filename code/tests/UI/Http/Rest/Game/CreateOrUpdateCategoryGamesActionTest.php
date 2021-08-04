<?php

declare(strict_types=1);

namespace App\Tests\UI\Http\Rest\Game;

use App\Domain\Game\Model\Game;
use App\Domain\Game\Model\Category;
use App\Tests\_helpers\DbPurger;
use App\Tests\_helpers\GameFixtures;
use App\Tests\_helpers\CategoryFixtures;
use App\Tests\_helpers\ResponseAsserter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateOrUpdateCategoryGamesActionTest extends WebTestCase
{
    use DbPurger;

    public function testCreateAndUpdateExisting(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);
        $gameFixtures = new GameFixtures($em);
        $gameFixtures->createGame('game_id_1', 1);
        $gameFixtures->createGame('game_id_2', 1);

        $payload = json_encode([
            'games' => [
                [
                    'game_id' => 'game_id_1',
                    'platform' => [
                        'desktop' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                            'platform_extra_field' => 'platform_extra_field_value',
                        ],
                        'mobile' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'ios' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'android' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                            'android_extra_field' => 'android_extra_value',
                        ],
                    ],
                    'some_extra_field' => 'extra_data',
                ],
                [
                    'game_id' => 'game_id_2',
                    'platform' => [
                        'desktop' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'mobile' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'ios' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'android' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                    ],
                    'some_extra_field2' => 'extra_data2',
                ],
                [
                    'game_id' => 'game_id_3',
                    'platform' => [
                        'desktop' => [
                            'category_game_id' => 'game_3',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'mobile' => [
                            'category_game_id' => 'game_3',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'ios' => [
                            'category_game_id' => 'game_3',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'android' => [
                            'category_game_id' => 'game_3',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                    ],
                    'some_extra_field3' => 'extra_data3',
                ],
            ],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('PUT', '/api/category-games/1', [], [], [], $payload);
        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $asserter = ResponseAsserter::getInstance();

        $asserter->assertResponsePropertyEquals($response, 'meta.total', 3);

        $asserter->assertResponsePropertyEquals($response, 'data.games[0].game_id', 'game_id_1');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].some_extra_field', 'extra_data');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.platform_extra_field', 'platform_extra_field_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.category_game_id', 'game_1');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.desktop.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].platform.android.android_extra_field', 'android_extra_value');
        $asserter->assertResponsePropertyEquals($response, 'data.games[0].category_id', 1);

        $asserter->assertResponsePropertyEquals($response, 'data.games[1].game_id', 'game_id_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].some_extra_field2', 'extra_data2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.category_game_id', 'game_2');
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].platform.mobile.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[1].category_id', 1);

        $asserter->assertResponsePropertyEquals($response, 'data.games[2].game_id', 'game_id_3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].some_extra_field3', 'extra_data3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.desktop.category_game_id', 'game_3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.desktop.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.desktop.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.mobile.category_game_id', 'game_3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.mobile.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.mobile.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.ios.category_game_id', 'game_3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.ios.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.ios.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.android.category_game_id', 'game_3');
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.android.enabled', false);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].platform.android.has_demo', true);
        $asserter->assertResponsePropertyEquals($response, 'data.games[2].category_id', 1);
    }

    public function testCreateWithValidationErrors(): void
    {
        $client = static::createClient();
        self::purgeEntities([Category::class, Game::class]);

        /** @var EntityManager $em */
        $em = self::$container->get('doctrine')->getManager('default');
        (new CategoryFixtures($em))->createCategory(1);

        $payload = json_encode([
            'games' => [
                [
                    'game_id' => 'game_id_1',
                    'platform' => [
                        'desktop' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'mobile' => [
                            'category_game_id' => 'game_1',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'ios' => [
                            'enabled' => 1,
                            'has_demo' => 1,
                        ],
                        'android' => [
                            'category_game_id' => '',
                            'enabled' => 1,
                            'has_demo' => 1,
                        ],
                    ],
                    'some_extra_field' => 'extra_data',
                ],
                [
                    'game_id' => '',
                    'platform' => [
                        'desktop' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'mobile' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'ios' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                        'android' => [
                            'category_game_id' => 'game_2',
                            'enabled' => 0,
                            'has_demo' => 1,
                        ],
                    ],
                    'some_extra_field2' => 'extra_data2',
                ],
            ],
        ], \JSON_THROW_ON_ERROR);

        $client->xmlHttpRequest('PUT', '/api/category-games/1', [], [], [], $payload);
        $response = $client->getResponse();

        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $asserter = ResponseAsserter::getInstance();
        $asserter->assertResponsePropertyEquals($response, 'errors.games[0][0]', 'Property "category_game_id" for "ios" platform must be set.');
        $asserter->assertResponsePropertyEquals($response, 'errors.games[0][1]', 'You must specify game name for "ios" platform.');
        $asserter->assertResponsePropertyEquals($response, 'errors.games[0][2]', 'You must specify game name for "android" platform.');

        $asserter->assertResponsePropertyEquals($response, 'errors.games[1][0]', 'Property "game_id" must be set.');
    }
}
