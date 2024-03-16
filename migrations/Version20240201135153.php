<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201135153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CA500F6B2');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CCC55B447');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA500F6B2 FOREIGN KEY (article_newsletter_id) REFERENCES article_newsletter (id)');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CCC55B447 FOREIGN KEY (user_newsletter_id) REFERENCES user_newsletter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CCC55B447');
        $this->addSql('ALTER TABLE user_article DROP FOREIGN KEY FK_5A37106CA500F6B2');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CCC55B447 FOREIGN KEY (user_newsletter_id) REFERENCES user_newsletter (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_article ADD CONSTRAINT FK_5A37106CA500F6B2 FOREIGN KEY (article_newsletter_id) REFERENCES article_newsletter (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
