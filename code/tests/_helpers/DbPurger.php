<?php

declare(strict_types=1);

namespace App\Tests\_helpers;

trait DbPurger
{
    /**
     * @param string $connectionName Like as `default`
     */
    public static function purgeEntities(array $entities, string $connectionName = 'default'): void
    {
        $em = self::$kernel->getContainer()->get('doctrine')->getManager($connectionName);

        $connection = $em->getConnection();
        $databasePlatform = $connection->getDatabasePlatform();
//        if ($databasePlatform->supportsForeignKeyConstraints()) {
//            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
//        }

//        DB::statement("TRUNCATE TABLE {$table} RESTART IDENTITY CASCADE");

        foreach ($entities as $entity) {
            $query = $databasePlatform->getTruncateTableSQL(
                $em->getClassMetadata($entity)->getTableName()
            );
            $connection->executeStatement($query);
        }
//        if ($databasePlatform->supportsForeignKeyConstraints()) {
//            $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
//        }
    }

    public static function clearEntityManager(string $emName = 'default'): void
    {
        $em = self::$container->get('doctrine')->getManager($emName);
        $em->clear();
    }
}
