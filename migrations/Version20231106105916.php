<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106105916 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche_fiscal ADD nb_personne_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tranche_fiscal ADD CONSTRAINT FK_980E4EA47F2169F8 FOREIGN KEY (nb_personne_id) REFERENCES personne (id)');
        $this->addSql('CREATE INDEX IDX_980E4EA47F2169F8 ON tranche_fiscal (nb_personne_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche_fiscal DROP FOREIGN KEY FK_980E4EA47F2169F8');
        $this->addSql('DROP INDEX IDX_980E4EA47F2169F8 ON tranche_fiscal');
        $this->addSql('ALTER TABLE tranche_fiscal DROP nb_personne_id');
    }
}
