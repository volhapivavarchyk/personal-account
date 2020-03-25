<?php

declare(strict_types=1);

namespace VP\PerosnalAccount\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325134517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE algorithm (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, content TEXT NOT NULL, INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_CD1DE18A727ACA70 (parent_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formula (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, content VARCHAR(256) NOT NULL, INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE functionality (id INT AUTO_INCREMENT NOT NULL, formula_id INT DEFAULT NULL, algorithm_id INT DEFAULT NULL, intelligence_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_F83C5F44A50A6386 (formula_id), INDEX IDX_F83C5F44BBEB6CF7 (algorithm_id), INDEX IDX_F83C5F447584E372 (intelligence_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE history (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_27BA704BA76ED395 (user_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intelligence (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, content JSON NOT NULL COMMENT \'(DC2Type:json_array)\', INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE interest (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_6C3E1A67A76ED395 (user_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_B6BD307FA76ED395 (user_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, description VARCHAR(128) NOT NULL, url VARCHAR(128) NOT NULL, INDEX IDX_C242628C54C8C93 (type_id), INDEX IDX_C242628727ACA70 (parent_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE modules_functionality (module_id INT NOT NULL, functionality_id INT NOT NULL, INDEX IDX_4202780AAFC2B591 (module_id), INDEX IDX_4202780A39EDDC8 (functionality_id), PRIMARY KEY(module_id, functionality_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, department_id INT DEFAULT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_462CE4F5AE80F5DF (department_id), INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE typemodule (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(128) NOT NULL, INDEX search_idx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(128) NOT NULL, password VARCHAR(128) NOT NULL, firstname VARCHAR(64) NOT NULL, lastname VARCHAR(64) NOT NULL, patronymic VARCHAR(64) NOT NULL, email VARCHAR(128) NOT NULL, api_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), INDEX search_idx (email, firstname, lastname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_roles (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_51498A8EA76ED395 (user_id), INDEX IDX_51498A8ED60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_positions (user_id INT NOT NULL, position_id INT NOT NULL, INDEX IDX_B0E29F9DA76ED395 (user_id), INDEX IDX_B0E29F9DDD842E46 (position_id), PRIMARY KEY(user_id, position_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A727ACA70 FOREIGN KEY (parent_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44A50A6386 FOREIGN KEY (formula_id) REFERENCES formula (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F44BBEB6CF7 FOREIGN KEY (algorithm_id) REFERENCES algorithm (id)');
        $this->addSql('ALTER TABLE functionality ADD CONSTRAINT FK_F83C5F447584E372 FOREIGN KEY (intelligence_id) REFERENCES intelligence (id)');
        $this->addSql('ALTER TABLE history ADD CONSTRAINT FK_27BA704BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE interest ADD CONSTRAINT FK_6C3E1A67A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628C54C8C93 FOREIGN KEY (type_id) REFERENCES typemodule (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628727ACA70 FOREIGN KEY (parent_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE modules_functionality ADD CONSTRAINT FK_4202780AAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE modules_functionality ADD CONSTRAINT FK_4202780A39EDDC8 FOREIGN KEY (functionality_id) REFERENCES functionality (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE position ADD CONSTRAINT FK_462CE4F5AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_roles ADD CONSTRAINT FK_51498A8ED60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_positions ADD CONSTRAINT FK_B0E29F9DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_positions ADD CONSTRAINT FK_B0E29F9DDD842E46 FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44BBEB6CF7');
        $this->addSql('ALTER TABLE department DROP FOREIGN KEY FK_CD1DE18A727ACA70');
        $this->addSql('ALTER TABLE position DROP FOREIGN KEY FK_462CE4F5AE80F5DF');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F44A50A6386');
        $this->addSql('ALTER TABLE modules_functionality DROP FOREIGN KEY FK_4202780A39EDDC8');
        $this->addSql('ALTER TABLE functionality DROP FOREIGN KEY FK_F83C5F447584E372');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628727ACA70');
        $this->addSql('ALTER TABLE modules_functionality DROP FOREIGN KEY FK_4202780AAFC2B591');
        $this->addSql('ALTER TABLE users_positions DROP FOREIGN KEY FK_B0E29F9DDD842E46');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8ED60322AC');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628C54C8C93');
        $this->addSql('ALTER TABLE history DROP FOREIGN KEY FK_27BA704BA76ED395');
        $this->addSql('ALTER TABLE interest DROP FOREIGN KEY FK_6C3E1A67A76ED395');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA76ED395');
        $this->addSql('ALTER TABLE users_roles DROP FOREIGN KEY FK_51498A8EA76ED395');
        $this->addSql('ALTER TABLE users_positions DROP FOREIGN KEY FK_B0E29F9DA76ED395');
        $this->addSql('DROP TABLE algorithm');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE formula');
        $this->addSql('DROP TABLE functionality');
        $this->addSql('DROP TABLE history');
        $this->addSql('DROP TABLE intelligence');
        $this->addSql('DROP TABLE interest');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE modules_functionality');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE typemodule');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_roles');
        $this->addSql('DROP TABLE users_positions');
    }
}
