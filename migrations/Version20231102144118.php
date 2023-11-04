<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102144118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche ADD type_devis_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_666758408C21DD26 FOREIGN KEY (type_devis_id) REFERENCES type_devis (id)');
        $this->addSql('CREATE INDEX IDX_666758408C21DD26 ON tranche (type_devis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_666758408C21DD26');
        $this->addSql('DROP INDEX IDX_666758408C21DD26 ON tranche');
        $this->addSql('ALTER TABLE tranche DROP type_devis_id');
    }
}
