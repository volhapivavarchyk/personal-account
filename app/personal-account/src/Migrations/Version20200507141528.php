<?php

declare(strict_types=1);

namespace VP\PerosnalAccount\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507141528 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE usertype (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD userkind_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AC01D017 FOREIGN KEY (userkind_id) REFERENCES usertype (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AC01D017 ON user (userkind_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AC01D017');
        $this->addSql('DROP TABLE usertype');
        $this->addSql('DROP INDEX IDX_8D93D649AC01D017 ON user');
        $this->addSql('ALTER TABLE user DROP userkind_id');
    }
}
