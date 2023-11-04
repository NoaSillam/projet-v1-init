<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231103110821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche ADD tranche_fiscal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_6667584031727F98 FOREIGN KEY (tranche_fiscal_id) REFERENCES tranche_fiscal (id)');
        $this->addSql('CREATE INDEX IDX_6667584031727F98 ON tranche (tranche_fiscal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_6667584031727F98');
        $this->addSql('DROP INDEX IDX_6667584031727F98 ON tranche');
        $this->addSql('ALTER TABLE tranche DROP tranche_fiscal_id');
    }
}
