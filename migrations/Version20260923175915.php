<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923175915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_attribute (id INT AUTO_INCREMENT NOT NULL, val_string VARCHAR(255) DEFAULT NULL, val_text LONGTEXT DEFAULT NULL, val_date DATE DEFAULT NULL, val_date_period DATE DEFAULT NULL, val_bool TINYINT DEFAULT NULL, val_dropdown VARCHAR(255) DEFAULT NULL, val_image VARCHAR(510) DEFAULT NULL, val_number NUMERIC(10, 2) DEFAULT NULL, attribute_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_FA9A621FB6E62EFA (attribute_id), INDEX IDX_FA9A621FA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE user_attribute ADD CONSTRAINT FK_FA9A621FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('ALTER TABLE user_attribute ADD CONSTRAINT FK_FA9A621FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY FK_FA9A621FB6E62EFA');
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY FK_FA9A621FA76ED395');
        $this->addSql('DROP TABLE user_attribute');
    }
}
