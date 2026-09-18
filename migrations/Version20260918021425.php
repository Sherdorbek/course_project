<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260918021425 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, string VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, num INT DEFAULT NULL, date DATE DEFAULT NULL, period LONGTEXT DEFAULT NULL, bool TINYINT DEFAULT NULL, one_of_many LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE attribute_cv ADD type VARCHAR(255) NOT NULL, ADD value_id INT NOT NULL');
        $this->addSql('ALTER TABLE attribute_cv ADD CONSTRAINT FK_BFB0B738F920BBA2 FOREIGN KEY (value_id) REFERENCES attribute_value (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFB0B738F920BBA2 ON attribute_cv (value_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_NAME ON attribute_cv (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE attribute_value');
        $this->addSql('ALTER TABLE attribute_cv DROP FOREIGN KEY FK_BFB0B738F920BBA2');
        $this->addSql('DROP INDEX UNIQ_BFB0B738F920BBA2 ON attribute_cv');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_NAME ON attribute_cv');
        $this->addSql('ALTER TABLE attribute_cv DROP type, DROP value_id');
    }
}
