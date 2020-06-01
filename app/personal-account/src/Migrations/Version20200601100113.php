<?php

declare(strict_types=1);

namespace VP\PerosnalAccount\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200601100113 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_studentsgroup DROP FOREIGN KEY FK_7B175545291AE46B');
        $this->addSql('CREATE TABLE studentgroup (id INT AUTO_INCREMENT NOT NULL, speciality_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_FC64F063B5A08D7 (speciality_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_studentgroup (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, studentgroup_id INT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, reason_demote VARCHAR(128) NOT NULL, INDEX IDX_8F0A3304A76ED395 (user_id), INDEX IDX_8F0A3304672A8DC2 (studentgroup_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE studentgroup ADD CONSTRAINT FK_FC64F063B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id)');
        $this->addSql('ALTER TABLE user_studentgroup ADD CONSTRAINT FK_8F0A3304A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_studentgroup ADD CONSTRAINT FK_8F0A3304672A8DC2 FOREIGN KEY (studentgroup_id) REFERENCES studentgroup (id)');
        $this->addSql('DROP TABLE studentsgroup');
        $this->addSql('DROP TABLE user_studentsgroup');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_studentgroup DROP FOREIGN KEY FK_8F0A3304672A8DC2');
        $this->addSql('CREATE TABLE studentsgroup (id INT AUTO_INCREMENT NOT NULL, speciality_id INT DEFAULT NULL, name VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_9599F8153B5A08D7 (speciality_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_studentsgroup (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, studentsgroup_id INT NOT NULL, date_start DATETIME NOT NULL, date_end DATETIME NOT NULL, reason_demote VARCHAR(128) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_7B175545291AE46B (studentsgroup_id), INDEX IDX_7B175545A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE studentsgroup ADD CONSTRAINT FK_9599F8153B5A08D7 FOREIGN KEY (speciality_id) REFERENCES speciality (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_studentsgroup ADD CONSTRAINT FK_7B175545291AE46B FOREIGN KEY (studentsgroup_id) REFERENCES studentsgroup (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_studentsgroup ADD CONSTRAINT FK_7B175545A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE studentgroup');
        $this->addSql('DROP TABLE user_studentgroup');
    }
}
