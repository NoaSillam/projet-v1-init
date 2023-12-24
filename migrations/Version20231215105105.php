<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231215105105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE devis_reno_global (id INT AUTO_INCREMENT NOT NULL, nb_personne_id INT DEFAULT NULL, regions_id INT DEFAULT NULL, tranche_fiscal_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, num_fiscal DOUBLE PRECISION NOT NULL, proprieter VARCHAR(255) NOT NULL, surface_habitable INT NOT NULL, telephone INT NOT NULL, type_chauffage VARCHAR(255) NOT NULL, residence_principale VARCHAR(255) NOT NULL, validations TINYINT(1) NOT NULL, installations VARCHAR(255) NOT NULL, validation_cee TINYINT(1) NOT NULL, INDEX IDX_455605B47F2169F8 (nb_personne_id), INDEX IDX_455605B4FCE83E5F (regions_id), INDEX IDX_455605B431727F98 (tranche_fiscal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE devis_reno_global ADD CONSTRAINT FK_455605B47F2169F8 FOREIGN KEY (nb_personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE devis_reno_global ADD CONSTRAINT FK_455605B4FCE83E5F FOREIGN KEY (regions_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE devis_reno_global ADD CONSTRAINT FK_455605B431727F98 FOREIGN KEY (tranche_fiscal_id) REFERENCES tranche_fiscal (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE devis_reno_global DROP FOREIGN KEY FK_455605B47F2169F8');
        $this->addSql('ALTER TABLE devis_reno_global DROP FOREIGN KEY FK_455605B4FCE83E5F');
        $this->addSql('ALTER TABLE devis_reno_global DROP FOREIGN KEY FK_455605B431727F98');
        $this->addSql('DROP TABLE devis_reno_global');
    }
}
