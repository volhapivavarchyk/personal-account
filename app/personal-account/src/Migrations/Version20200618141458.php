<?php

declare(strict_types=1);

namespace VP\PerosnalAccount\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618141458 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('ALTER TABLE user_position CHANGE date_start date_start DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE reason_demote reason_demote VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE user_studentgroup CHANGE date_start date_start DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE reason_demote reason_demote VARCHAR(128) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user_position CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL, CHANGE reason_demote reason_demote VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user_studentgroup CHANGE date_start date_start DATETIME NOT NULL, CHANGE date_end date_end DATETIME NOT NULL, CHANGE reason_demote reason_demote VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
