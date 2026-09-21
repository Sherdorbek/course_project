<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921162746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position_attribute_cv (position_id INT NOT NULL, attribute_cv_id INT NOT NULL, INDEX IDX_B6EB6D71DD842E46 (position_id), INDEX IDX_B6EB6D712CDCFA54 (attribute_cv_id), PRIMARY KEY (position_id, attribute_cv_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE position_attribute_cv ADD CONSTRAINT FK_B6EB6D71DD842E46 FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE position_attribute_cv ADD CONSTRAINT FK_B6EB6D712CDCFA54 FOREIGN KEY (attribute_cv_id) REFERENCES attribute_cv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE position_attr DROP FOREIGN KEY `FK_17E60D4AB6E62EFA`');
        $this->addSql('ALTER TABLE position_attr DROP FOREIGN KEY `FK_17E60D4ADD842E46`');
        $this->addSql('DROP TABLE position_attr');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE position_attr (id INT AUTO_INCREMENT NOT NULL, position_id INT NOT NULL, attribute_id INT NOT NULL, row_order INT NOT NULL, INDEX IDX_17E60D4ADD842E46 (position_id), INDEX IDX_17E60D4AB6E62EFA (attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE position_attr ADD CONSTRAINT `FK_17E60D4AB6E62EFA` FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('ALTER TABLE position_attr ADD CONSTRAINT `FK_17E60D4ADD842E46` FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('ALTER TABLE position_attribute_cv DROP FOREIGN KEY FK_B6EB6D71DD842E46');
        $this->addSql('ALTER TABLE position_attribute_cv DROP FOREIGN KEY FK_B6EB6D712CDCFA54');
        $this->addSql('DROP TABLE position_attribute_cv');
    }
}
