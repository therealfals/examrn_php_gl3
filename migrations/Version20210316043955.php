<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316043955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_cv (categorie_id INT NOT NULL, cv_id INT NOT NULL, INDEX IDX_B5A37EEFBCF5E72D (categorie_id), INDEX IDX_B5A37EEFCFE419E2 (cv_id), PRIMARY KEY(categorie_id, cv_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categorie_cv ADD CONSTRAINT FK_B5A37EEFBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie_cv ADD CONSTRAINT FK_B5A37EEFCFE419E2 FOREIGN KEY (cv_id) REFERENCES cv (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie_cv DROP FOREIGN KEY FK_B5A37EEFBCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_cv');
    }
}
