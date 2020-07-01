<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200630113720 extends AbstractMigration
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
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE reponse_utilisateur ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE reponse_utilisateur ADD CONSTRAINT FK_14B756B61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_14B756B61E27F6BF ON reponse_utilisateur (question_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494ece07e8ff TO FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE reponse_utilisateur DROP FOREIGN KEY FK_14B756B61E27F6BF');
        $this->addSql('DROP INDEX IDX_14B756B61E27F6BF ON reponse_utilisateur');
        $this->addSql('ALTER TABLE reponse_utilisateur DROP question_id');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE score score INT DEFAULT 0');
    }
}
