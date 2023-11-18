<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106104120 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis DROP FOREIGN KEY FK_C4DF25095468E662');
        $this->addSql('DROP INDEX IDX_C4DF25095468E662 ON infos_devis');
        $this->addSql('ALTER TABLE infos_devis ADD nb_personne_id INT DEFAULT NULL, DROP nb_personnes_id');
        $this->addSql('ALTER TABLE infos_devis ADD CONSTRAINT FK_C4DF25097F2169F8 FOREIGN KEY (nb_personne_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_C4DF25097F2169F8 ON infos_devis (nb_personne_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis DROP FOREIGN KEY FK_C4DF25097F2169F8');
        $this->addSql('DROP INDEX IDX_C4DF25097F2169F8 ON infos_devis');
        $this->addSql('ALTER TABLE infos_devis ADD nb_personnes_id INT NOT NULL, DROP nb_personne_id');
        $this->addSql('ALTER TABLE infos_devis ADD CONSTRAINT FK_C4DF25095468E662 FOREIGN KEY (nb_personnes_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_C4DF25095468E662 ON infos_devis (nb_personnes_id)');
    }
}
