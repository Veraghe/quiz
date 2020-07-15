<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200715071933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE anonyme (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_de_question DROP FOREIGN KEY FK_89635473CE07E8FF');
        $this->addSql('DROP INDEX IDX_89635473CE07E8FF ON type_de_question');
        $this->addSql('ALTER TABLE type_de_question DROP questionnaire_id');
        $this->addSql('ALTER TABLE question RENAME INDEX fk_b6f7494ece07e8ff TO IDX_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE reponse_utilisateur ADD anonyme_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_utilisateur ADD CONSTRAINT FK_14B756B6433B2C47 FOREIGN KEY (anonyme_id) REFERENCES anonyme (id)');
        $this->addSql('CREATE INDEX IDX_14B756B6433B2C47 ON reponse_utilisateur (anonyme_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reponse_utilisateur DROP FOREIGN KEY FK_14B756B6433B2C47');
        $this->addSql('DROP TABLE anonyme');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494ece07e8ff TO FK_B6F7494ECE07E8FF');
        $this->addSql('DROP INDEX IDX_14B756B6433B2C47 ON reponse_utilisateur');
        $this->addSql('ALTER TABLE reponse_utilisateur DROP anonyme_id, CHANGE date date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE type_de_question ADD questionnaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE type_de_question ADD CONSTRAINT FK_89635473CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('CREATE INDEX IDX_89635473CE07E8FF ON type_de_question (questionnaire_id)');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3613FECDF');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE score score INT DEFAULT 0');
    }
}
