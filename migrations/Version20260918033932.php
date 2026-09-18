<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260918033932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_cv DROP FOREIGN KEY `FK_BFB0B738F920BBA2`');
        $this->addSql('DROP INDEX UNIQ_BFB0B738F920BBA2 ON attribute_cv');
        $this->addSql('ALTER TABLE attribute_cv DROP value_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_cv ADD value_id INT NOT NULL');
        $this->addSql('ALTER TABLE attribute_cv ADD CONSTRAINT `FK_BFB0B738F920BBA2` FOREIGN KEY (value_id) REFERENCES attribute_value (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFB0B738F920BBA2 ON attribute_cv (value_id)');
    }
}
