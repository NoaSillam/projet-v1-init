<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201101932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prime DROP FOREIGN KEY FK_544B0F574EFCE069');
        $this->addSql('DROP INDEX IDX_544B0F574EFCE069 ON prime');
        $this->addSql('ALTER TABLE prime DROP type_chauffage_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prime ADD type_chauffage_id INT NOT NULL');
        $this->addSql('ALTER TABLE prime ADD CONSTRAINT FK_544B0F574EFCE069 FOREIGN KEY (type_chauffage_id) REFERENCES type_devis (id)');
        $this->addSql('CREATE INDEX IDX_544B0F574EFCE069 ON prime (type_chauffage_id)');
    }
}
