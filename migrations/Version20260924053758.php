<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260924053758 extends AbstractMigration
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
        $this->addSql('ALTER TABLE cv_position DROP FOREIGN KEY `FK_D7030131CFE419E2`');
        $this->addSql('ALTER TABLE cv_position DROP FOREIGN KEY `FK_D7030131DD842E46`');
        $this->addSql('DROP TABLE cv_position');
        $this->addSql('ALTER TABLE attribute_cv ADD is_removable TINYINT NOT NULL, ADD deleteted_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE cv ADD first_name VARCHAR(255) NOT NULL, ADD second_name VARCHAR(255) NOT NULL, ADD email VARCHAR(255) NOT NULL, ADD position_id INT NOT NULL');
        $this->addSql('ALTER TABLE cv ADD CONSTRAINT FK_B66FFE92DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
        $this->addSql('CREATE INDEX IDX_B66FFE92DD842E46 ON cv (position_id)');
        $this->addSql('ALTER TABLE cv_attribute ADD cv_id INT NOT NULL');
        $this->addSql('ALTER TABLE cv_attribute ADD CONSTRAINT FK_66E2C6B7CFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('CREATE INDEX IDX_66E2C6B7CFE419E2 ON cv_attribute (cv_id)');
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL, DROP roles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cv_position (cv_id INT NOT NULL, position_id INT NOT NULL, INDEX IDX_D7030131CFE419E2 (cv_id), INDEX IDX_D7030131DD842E46 (position_id), PRIMARY KEY (cv_id, position_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cv_position ADD CONSTRAINT `FK_D7030131CFE419E2` FOREIGN KEY (cv_id) REFERENCES cv (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cv_position ADD CONSTRAINT `FK_D7030131DD842E46` FOREIGN KEY (position_id) REFERENCES position (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY FK_FA9A621FB6E62EFA');
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY FK_FA9A621FA76ED395');
        $this->addSql('DROP TABLE user_attribute');
        $this->addSql('ALTER TABLE attribute_cv DROP is_removable, DROP deleteted_at');
        $this->addSql('ALTER TABLE cv DROP FOREIGN KEY FK_B66FFE92DD842E46');
        $this->addSql('DROP INDEX IDX_B66FFE92DD842E46 ON cv');
        $this->addSql('ALTER TABLE cv DROP first_name, DROP second_name, DROP email, DROP position_id');
        $this->addSql('ALTER TABLE cv_attribute DROP FOREIGN KEY FK_66E2C6B7CFE419E2');
        $this->addSql('DROP INDEX IDX_66E2C6B7CFE419E2 ON cv_attribute');
        $this->addSql('ALTER TABLE cv_attribute DROP cv_id');
        $this->addSql('ALTER TABLE `user` ADD roles JSON NOT NULL, DROP role');
    }
}
