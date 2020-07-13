<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200713124616 extends AbstractMigration
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
        $this->addSql('ALTER TABLE utilisateur ADD session_id INT NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_1D1C63B3613FECDF ON utilisateur (session_id)');
        $this->addSql('ALTER TABLE reponse_utilisateur CHANGE date date DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494ece07e8ff TO FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE reponse_utilisateur CHANGE date date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3613FECDF');
        $this->addSql('DROP INDEX IDX_1D1C63B3613FECDF ON utilisateur');
        $this->addSql('ALTER TABLE utilisateur DROP session_id, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE score score INT DEFAULT 0');
    }
}
