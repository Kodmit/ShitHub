<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210813210025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initialize daily and tasks';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dailys (id UUID NOT NULL, user_id UUID NOT NULL, description TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6E7DAE6A76ED395 ON dailys (user_id)');
        $this->addSql('COMMENT ON COLUMN dailys.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN dailys.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE tasks (id UUID NOT NULL, daily_id UUID NOT NULL, user_id UUID NOT NULL, description TEXT NOT NULL, done BOOLEAN NOT NULL, cancelled BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50586597E04C0193 ON tasks (daily_id)');
        $this->addSql('CREATE INDEX IDX_50586597A76ED395 ON tasks (user_id)');
        $this->addSql('COMMENT ON COLUMN tasks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN tasks.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE dailys ADD CONSTRAINT FK_B6E7DAE6A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597E04C0193 FOREIGN KEY (daily_id) REFERENCES dailys (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tasks ADD CONSTRAINT FK_50586597A76ED395 FOREIGN KEY (user_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE users ALTER id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tasks DROP CONSTRAINT FK_50586597E04C0193');
        $this->addSql('DROP TABLE dailys');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('ALTER TABLE "users" ALTER id TYPE UUID');
        $this->addSql('ALTER TABLE "users" ALTER id DROP DEFAULT');
    }
}
