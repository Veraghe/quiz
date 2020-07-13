<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200713075449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, libelle_session VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question RENAME INDEX fk_b6f7494ece07e8ff TO IDX_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE reponse_utilisateur CHANGE date date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE type_de_question ADD CONSTRAINT FK_89635473CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', CHANGE score score INT NOT NULL');
        $this->addSql('ALTER TABLE reponses_utilisateur CHANGE questionnaire_id questionnaire_id INT DEFAULT NULL, CHANGE reponse0 reponse0 LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE reponse1 reponse1 LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponses_utilisateur RENAME INDEX fk_1a067cf9ce07e8ff TO IDX_1A067CF9CE07E8FF');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE session');
        $this->addSql('ALTER TABLE question RENAME INDEX idx_b6f7494ece07e8ff TO FK_B6F7494ECE07E8FF');
        $this->addSql('ALTER TABLE reponse_utilisateur CHANGE date date DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE reponses_utilisateur CHANGE questionnaire_id questionnaire_id INT DEFAULT NULL, CHANGE reponse0 reponse0 LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE reponse1 reponse1 LONGTEXT CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE reponses_utilisateur RENAME INDEX idx_1a067cf9ce07e8ff TO FK_1A067CF9CE07E8FF');
        $this->addSql('ALTER TABLE type_de_question DROP FOREIGN KEY FK_89635473CE07E8FF');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE score score INT DEFAULT 0');
    }
}
