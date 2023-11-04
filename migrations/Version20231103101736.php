<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103101736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE infos_devis (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, num_fiscal DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infos_devis_tranche (infos_devis_id INT NOT NULL, tranche_id INT NOT NULL, INDEX IDX_6F1854CC970FE1D9 (infos_devis_id), INDEX IDX_6F1854CCB76F6B31 (tranche_id), PRIMARY KEY(infos_devis_id, tranche_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE infos_devis_tranche ADD CONSTRAINT FK_6F1854CC970FE1D9 FOREIGN KEY (infos_devis_id) REFERENCES infos_devis (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE infos_devis_tranche ADD CONSTRAINT FK_6F1854CCB76F6B31 FOREIGN KEY (tranche_id) REFERENCES tranche (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis_tranche DROP FOREIGN KEY FK_6F1854CC970FE1D9');
        $this->addSql('ALTER TABLE infos_devis_tranche DROP FOREIGN KEY FK_6F1854CCB76F6B31');
        $this->addSql('DROP TABLE infos_devis');
        $this->addSql('DROP TABLE infos_devis_tranche');
    }
}
