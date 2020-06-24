<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200603081754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A460BB6FE6');
        $this->addSql('ALTER TABLE livres DROP FOREIGN KEY FK_927187A4BCF5E72D');
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, libelle_reponse VARCHAR(255) NOT NULL, valeur_reponse INT NOT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_de_question (id INT AUTO_INCREMENT NOT NULL, questionnaire_id INT NOT NULL, libelle_type_de_question VARCHAR(255) NOT NULL, INDEX IDX_89635473CE07E8FF (questionnaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, type_de_question_id INT NOT NULL, libelle_question VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E3A9ABB02 (type_de_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire (id INT AUTO_INCREMENT NOT NULL, libelle_questionnaire VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE type_de_question ADD CONSTRAINT FK_89635473CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E3A9ABB02 FOREIGN KEY (type_de_question_id) REFERENCES type_de_question (id)');
        $this->addSql('DROP TABLE auteurs');
        $this->addSql('DROP TABLE bibliotheque');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE livres');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E3A9ABB02');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE type_de_question DROP FOREIGN KEY FK_89635473CE07E8FF');
        $this->addSql('CREATE TABLE auteurs (id INT AUTO_INCREMENT NOT NULL, libelle_auteur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bibliotheque (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, auteur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, libelle_categorie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livres (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, categorie_id INT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_927187A460BB6FE6 (auteur_id), INDEX IDX_927187A4BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A460BB6FE6 FOREIGN KEY (auteur_id) REFERENCES auteurs (id)');
        $this->addSql('ALTER TABLE livres ADD CONSTRAINT FK_927187A4BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE type_de_question');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE questionnaire');
    }
}
