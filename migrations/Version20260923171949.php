<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260923171949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv_attribute ADD cv_id INT NOT NULL');
        $this->addSql('ALTER TABLE cv_attribute ADD CONSTRAINT FK_66E2C6B7CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_66E2C6B7CFE419E2 ON cv_attribute (cv_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cv_attribute DROP FOREIGN KEY FK_66E2C6B7CFE419E2');
        $this->addSql('DROP INDEX IDX_66E2C6B7CFE419E2 ON cv_attribute');
        $this->addSql('ALTER TABLE cv_attribute DROP cv_id');
    }
}
