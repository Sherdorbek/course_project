<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260919050903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE one_of_many (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(255) NOT NULL, attribute_id INT NOT NULL, INDEX IDX_B6994B79B6E62EFA (attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE one_of_many ADD CONSTRAINT FK_B6994B79B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('ALTER TABLE attribute_cv DROP options');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE one_of_many DROP FOREIGN KEY FK_B6994B79B6E62EFA');
        $this->addSql('DROP TABLE one_of_many');
        $this->addSql('ALTER TABLE attribute_cv ADD options LONGTEXT DEFAULT NULL');
    }
}
