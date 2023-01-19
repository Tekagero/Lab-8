<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220419171349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment ADD news_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment RENAME COLUMN test TO text');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526CB5A459A0 ON comment (news_id)');
        $this->addSql('ALTER TABLE "user" ADD phone VARCHAR(25) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP phone');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CB5A459A0');
        $this->addSql('DROP INDEX IDX_9474526CB5A459A0');
        $this->addSql('ALTER TABLE comment DROP news_id');
        $this->addSql('ALTER TABLE comment RENAME COLUMN text TO test');
    }
}
