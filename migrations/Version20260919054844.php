<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260919054844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, public TINYINT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE position_attr (id INT AUTO_INCREMENT NOT NULL, position_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_17E60D4ADD842E46 (position_id), INDEX IDX_17E60D4AB6E62EFA (attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE position_attr ADD CONSTRAINT FK_17E60D4ADD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE position_attr ADD CONSTRAINT FK_17E60D4AB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('DROP TABLE attribute_value');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute_value (id INT AUTO_INCREMENT NOT NULL, string VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, text LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, num INT DEFAULT NULL, date DATE DEFAULT NULL, period LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, bool TINYINT DEFAULT NULL, one_of_many LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_uca1400_ai_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE position_attr DROP FOREIGN KEY FK_17E60D4ADD842E46');
        $this->addSql('ALTER TABLE position_attr DROP FOREIGN KEY FK_17E60D4AB6E62EFA');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE position_attr');
    }
}
