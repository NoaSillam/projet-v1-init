<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201102551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prime DROP FOREIGN KEY FK_544B0F5775E5878B');
        $this->addSql('DROP INDEX IDX_544B0F5775E5878B ON prime');
        $this->addSql('ALTER TABLE prime DROP menage_id, DROP aide');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE prime ADD menage_id INT NOT NULL, ADD aide INT NOT NULL');
        $this->addSql('ALTER TABLE prime ADD CONSTRAINT FK_544B0F5775E5878B FOREIGN KEY (menage_id) REFERENCES menage (id)');
        $this->addSql('CREATE INDEX IDX_544B0F5775E5878B ON prime (menage_id)');
    }
}
