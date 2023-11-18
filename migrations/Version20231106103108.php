<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106103108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis ADD nb_personnes_id INT NOT NULL');
        $this->addSql('ALTER TABLE infos_devis ADD CONSTRAINT FK_C4DF25095468E662 FOREIGN KEY (nb_personnes_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_C4DF25095468E662 ON infos_devis (nb_personnes_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis DROP FOREIGN KEY FK_C4DF25095468E662');
        $this->addSql('DROP INDEX IDX_C4DF25095468E662 ON infos_devis');
        $this->addSql('ALTER TABLE infos_devis DROP nb_personnes_id');
    }
}
