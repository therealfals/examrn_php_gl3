<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315205333 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cv (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, age INT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, specialite VARCHAR(255) DEFAULT NULL, niveau_etude VARCHAR(255) DEFAULT NULL, experience_professionnelle LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demandeur_emploi (id INT AUTO_INCREMENT NOT NULL, cv_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CA2E807CE7927C74 (email), UNIQUE INDEX UNIQ_CA2E807CCFE419E2 (cv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D19FA60E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demandeur_emploi ADD CONSTRAINT FK_CA2E807CCFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id)');
        $this->addSql('ALTER TABLE user DROP roles');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demandeur_emploi DROP FOREIGN KEY FK_CA2E807CCFE419E2');
        $this->addSql('DROP TABLE cv');
        $this->addSql('DROP TABLE demandeur_emploi');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('ALTER TABLE user ADD roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\'');
    }
}
