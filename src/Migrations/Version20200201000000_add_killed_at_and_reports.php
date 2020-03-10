<?php declare(strict_types=1);

namespace DoctrineMigrations;

use JobBoy\Process\Domain\Repository\Infrastructure\Doctrine\Migrations\Version20200201000000_add_killed_at_and_reports as BaseVersion;

/**
 * Doctrine DBAL Migration of JobBoy ProcessRepository (for Symfony)
 *
 * You can prevent the doctrine:migrations:diff command from dropping the table
 * by setting the $tableName parameter in broadway_event_store_dbal.yaml to ...
 *
 *     - "__domain_events"
 *
 * ... and adding ...
 *
 *     schema_filter: ~^(?!__)~"
 *
 * ... to doctrine.yaml (under the "dbal" section). This will tell Doctrine to
 * ignore all tables starting with "__" (two underscores).
 *
 * @author Andreas Gustafsson <arrgson@gmail.com>
 */
final class Version20200201000000_add_killed_at_and_reports extends BaseVersion
{
}