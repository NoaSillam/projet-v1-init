<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106141707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis ADD tranche_fiscal_id INT NOT NULL');
        $this->addSql('ALTER TABLE infos_devis ADD CONSTRAINT FK_C4DF250931727F98 FOREIGN KEY (tranche_fiscal_id) REFERENCES tranche_fiscal (id)');
        $this->addSql('CREATE INDEX IDX_C4DF250931727F98 ON infos_devis (tranche_fiscal_id)');
        $this->addSql('ALTER TABLE infos_devis_tranche DROP FOREIGN KEY FK_6F1854CC970FE1D9');
        $this->addSql('ALTER TABLE infos_devis_tranche ADD CONSTRAINT FK_6F1854CC970FE1D9 FOREIGN KEY (infos_devis_id) REFERENCES infos_devis (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis DROP FOREIGN KEY FK_C4DF250931727F98');
        $this->addSql('DROP INDEX IDX_C4DF250931727F98 ON infos_devis');
        $this->addSql('ALTER TABLE infos_devis DROP tranche_fiscal_id');
        $this->addSql('ALTER TABLE infos_devis_tranche DROP FOREIGN KEY FK_6F1854CC970FE1D9');
        $this->addSql('ALTER TABLE infos_devis_tranche ADD CONSTRAINT FK_6F1854CC970FE1D9 FOREIGN KEY (infos_devis_id) REFERENCES infos_devis (id) ON DELETE CASCADE');
    }
}
