<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261001102033 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE attribute_cv (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, is_removable TINYINT NOT NULL, version INT NOT NULL, category_id INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_NAME (name), INDEX IDX_BFB0B73812469DE2 (category_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, updated_at DATETIME NOT NULL, position_id INT NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_CV (position_id, user_id), INDEX IDX_B66FFE92DD842E46 (position_id), INDEX IDX_B66FFE92A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE likes (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_LIKE (cv_id, user_id), INDEX IDX_49CA4E7DCFE419E2 (cv_id), INDEX IDX_49CA4E7DA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE one_of_many (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, attribute_id INT NOT NULL, INDEX IDX_B6994B79B6E62EFA (attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, project_number INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE position_attribute_cv (position_id INT NOT NULL, attribute_cv_id INT NOT NULL, INDEX IDX_B6EB6D71DD842E46 (position_id), INDEX IDX_B6EB6D712CDCFA54 (attribute_cv_id), PRIMARY KEY (position_id, attribute_cv_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, date1 DATE NOT NULL, date2 DATE NOT NULL, user_id INT NOT NULL, INDEX IDX_2FB3D0EEA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, google_id VARCHAR(255) DEFAULT NULL, facebook_id VARCHAR(255) DEFAULT NULL, profile_set_up TINYINT NOT NULL, theme TINYINT NOT NULL, locale VARCHAR(15) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user_attribute` (id INT AUTO_INCREMENT NOT NULL, val_string VARCHAR(255) DEFAULT NULL, val_text LONGTEXT DEFAULT NULL, val_date DATE DEFAULT NULL, val_date_period DATE DEFAULT NULL, val_bool TINYINT DEFAULT NULL, val_dropdown VARCHAR(255) DEFAULT NULL, val_image VARCHAR(510) DEFAULT NULL, val_number NUMERIC(10, 1) DEFAULT NULL, attribute_id INT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_ATTRIBUTE (user_id, attribute_id), INDEX IDX_FA9A621FB6E62EFA (attribute_id), INDEX IDX_FA9A621FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attribute_cv ADD CONSTRAINT FK_BFB0B73812469DE2 FOREIGN KEY (category_id) REFERENCES attribute_category (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DCFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE one_of_many ADD CONSTRAINT FK_B6994B79B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('ALTER TABLE position_attribute_cv ADD CONSTRAINT FK_B6EB6D71DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE position_attribute_cv ADD CONSTRAINT FK_B6EB6D712CDCFA54 FOREIGN KEY (attribute_cv_id) REFERENCES attribute_cv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user_attribute` ADD CONSTRAINT FK_FA9A621FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user_attribute` ADD CONSTRAINT FK_FA9A621FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_cv DROP FOREIGN KEY FK_BFB0B73812469DE2');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92DD842E46');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92A76ED395');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DCFE419E2');
        $this->addSql('ALTER TABLE likes DROP FOREIGN KEY FK_49CA4E7DA76ED395');
        $this->addSql('ALTER TABLE one_of_many DROP FOREIGN KEY FK_B6994B79B6E62EFA');
        $this->addSql('ALTER TABLE position_attribute_cv DROP FOREIGN KEY FK_B6EB6D71DD842E46');
        $this->addSql('ALTER TABLE position_attribute_cv DROP FOREIGN KEY FK_B6EB6D712CDCFA54');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEA76ED395');
        $this->addSql('ALTER TABLE `user_attribute` DROP FOREIGN KEY FK_FA9A621FB6E62EFA');
        $this->addSql('ALTER TABLE `user_attribute` DROP FOREIGN KEY FK_FA9A621FA76ED395');
        $this->addSql('DROP TABLE attribute_category');
        $this->addSql('DROP TABLE attribute_cv');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE likes');
        $this->addSql('DROP TABLE one_of_many');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE position_attribute_cv');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE `user_attribute`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
