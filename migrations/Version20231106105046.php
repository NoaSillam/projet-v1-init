<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106105046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis ADD regions_id INT NOT NULL');
        $this->addSql('ALTER TABLE infos_devis ADD CONSTRAINT FK_C4DF2509FCE83E5F FOREIGN KEY (regions_id) REFERENCES regions (id)');
        $this->addSql('CREATE INDEX IDX_C4DF2509FCE83E5F ON infos_devis (regions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE infos_devis DROP FOREIGN KEY FK_C4DF2509FCE83E5F');
        $this->addSql('DROP INDEX IDX_C4DF2509FCE83E5F ON infos_devis');
        $this->addSql('ALTER TABLE infos_devis DROP regions_id');
    }
}
