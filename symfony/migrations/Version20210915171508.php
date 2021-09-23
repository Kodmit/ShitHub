<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915171508 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_50586597A76ED395');
        $this->addSql('ALTER TABLE tasks ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE tasks ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE tasks ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE tasks ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "users" ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE "users" ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT fk_50586597a76ed395');
        $this->addSql('ALTER TABLE tasks ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE tasks ALTER id DROP DEFAULT');
        $this->addSql('ALTER TABLE tasks ALTER user_id TYPE UUID');
        $this->addSql('ALTER TABLE tasks ALTER user_id DROP DEFAULT');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT fk_50586597a76ed395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
