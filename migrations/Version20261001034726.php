<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261001034726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY `FK_FA9A621FA76ED395`');
        $this->addSql('ALTER TABLE user_attribute DROP FOREIGN KEY `FK_FA9A621FB6E62EFA`');
        $this->addSql('ALTER TABLE user_attribute ADD CONSTRAINT FK_FA9A621FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_attribute ADD CONSTRAINT FK_FA9A621FB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user_attribute` DROP FOREIGN KEY FK_FA9A621FB6E62EFA');
        $this->addSql('ALTER TABLE `user_attribute` DROP FOREIGN KEY FK_FA9A621FA76ED395');
        $this->addSql('ALTER TABLE `user_attribute` ADD CONSTRAINT `FK_FA9A621FB6E62EFA` FOREIGN KEY (attribute_id) REFERENCES attribute_cv (id)');
        $this->addSql('ALTER TABLE `user_attribute` ADD CONSTRAINT `FK_FA9A621FA76ED395` FOREIGN KEY (user_id) REFERENCES attribute_cv (id) ON DELETE CASCADE');
    }
}
