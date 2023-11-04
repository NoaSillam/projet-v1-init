<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102141531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tranche (id INT AUTO_INCREMENT NOT NULL, menage_id INT NOT NULL, region_id INT NOT NULL, nb_personne_id INT NOT NULL, prime_id INT NOT NULL, debut INT NOT NULL, fin INT NOT NULL, INDEX IDX_6667584075E5878B (menage_id), INDEX IDX_6667584098260155 (region_id), INDEX IDX_666758407F2169F8 (nb_personne_id), INDEX IDX_6667584069247986 (prime_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_6667584075E5878B FOREIGN KEY (menage_id) REFERENCES menage (id)');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_6667584098260155 FOREIGN KEY (region_id) REFERENCES regions (id)');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_666758407F2169F8 FOREIGN KEY (nb_personne_id) REFERENCES personne (id)');
        $this->addSql('ALTER TABLE tranche ADD CONSTRAINT FK_6667584069247986 FOREIGN KEY (prime_id) REFERENCES prime (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_6667584075E5878B');
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_6667584098260155');
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_666758407F2169F8');
        $this->addSql('ALTER TABLE tranche DROP FOREIGN KEY FK_6667584069247986');
        $this->addSql('DROP TABLE tranche');
    }
}
