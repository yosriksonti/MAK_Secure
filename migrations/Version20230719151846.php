<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230719151846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Picture (id INT AUTO_INCREMENT NOT NULL, createdAt DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updatedAt DATETIME NOT NULL, image VARCHAR(255) NOT NULL, Auto INT DEFAULT NULL, INDEX Auto (Auto), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auto (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(255) NOT NULL, modele VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, boite VARCHAR(255) NOT NULL, carb VARCHAR(255) NOT NULL, nb_places INT NOT NULL, nb_portes INT NOT NULL, nb_val INT NOT NULL, clim TINYINT(1) NOT NULL, description VARCHAR(255) NOT NULL, description_det LONGTEXT NOT NULL, updated_at DATETIME NOT NULL, is_vat TINYINT(1) NOT NULL, matricule VARCHAR(255) NOT NULL, carte_grise VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, kilos DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Picture ADD CONSTRAINT FK_D9667615C6888AC4 FOREIGN KEY (Auto) REFERENCES auto (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Picture DROP FOREIGN KEY FK_D9667615C6888AC4');
        $this->addSql('DROP TABLE Picture');
        $this->addSql('DROP TABLE auto');
    }
}
