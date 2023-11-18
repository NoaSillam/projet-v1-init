<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114102714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche_fiscal ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tranche_fiscal ADD CONSTRAINT FK_980E4EA498260155 FOREIGN KEY (region_id) REFERENCES regions (id)');
        $this->addSql('CREATE INDEX IDX_980E4EA498260155 ON tranche_fiscal (region_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche_fiscal DROP FOREIGN KEY FK_980E4EA498260155');
        $this->addSql('DROP INDEX IDX_980E4EA498260155 ON tranche_fiscal');
        $this->addSql('ALTER TABLE tranche_fiscal DROP region_id');
    }
}
