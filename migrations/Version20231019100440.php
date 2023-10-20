<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231019100440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_newsletter (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, annonce LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_article (id INT AUTO_INCREMENT NOT NULL, user_newsletter_id INT NOT NULL, article_newsletter_id INT NOT NULL, INDEX IDX_5A37106CCC55B447 (user_newsletter_id), INDEX IDX_5A37106CA500F6B2 (article_newsletter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_newsletter (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, adresse_mail VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CCC55B447 FOREIGN KEY (user_newsletter_id) REFERENCES user_newsletter (id)');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA500F6B2 FOREIGN KEY (article_newsletter_id) REFERENCES article_newsletter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CCC55B447');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CA500F6B2');
        $this->addSql('DROP TABLE article_newsletter');
        $this->addSql('DROP TABLE user_article');
        $this->addSql('DROP TABLE user_newsletter');
    }
}
