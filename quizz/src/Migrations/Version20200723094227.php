<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723094227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question RENAME INDEX fk_b6f7494ece07e8ff TO IDX_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE utilisateur DROP score, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponse_utilisateur ADD image INT DEFAULT NULL, ADD reponse_image INT DEFAULT NULL, CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL, CHANGE anonyme_id anonyme_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_utilisateur RENAME INDEX fk_14b756b6433b2c47 TO IDX_14B756B6433B2C47');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494ece07e8ff TO FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE reponse_utilisateur DROP image, DROP reponse_image, CHANGE utilisateur_id utilisateur_id INT DEFAULT NULL, CHANGE anonyme_id anonyme_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reponse_utilisateur RENAME INDEX idx_14b756b6433b2c47 TO FK_14B756B6433B2C47');
        $this->addSql('ALTER TABLE utilisateur ADD score INT DEFAULT 0, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
