<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830101732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'List Entity Creation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE todos_list (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todo AS SELECT id, name, created_at, expires_at, status FROM todo');
        $this->addSql('DROP TABLE todo');
        $this->addSql('CREATE TABLE todo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, list_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, created_at DATETIME NOT NULL, expires_at DATE NOT NULL, status VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_5A0EB6A03DAE168B FOREIGN KEY (list_id) REFERENCES todos_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO todo (id, name, created_at, expires_at, status) SELECT id, name, created_at, expires_at, status FROM __temp__todo');
        $this->addSql('DROP TABLE __temp__todo');
        $this->addSql('CREATE INDEX IDX_5A0EB6A03DAE168B ON todo (list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE todos_list');
        $this->addSql('DROP INDEX IDX_5A0EB6A03DAE168B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todo AS SELECT id, name, created_at, expires_at, status FROM todo');
        $this->addSql('DROP TABLE todo');
        $this->addSql('CREATE TABLE todo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, expires_at DATE NOT NULL, status VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO todo (id, name, created_at, expires_at, status) SELECT id, name, created_at, expires_at, status FROM __temp__todo');
        $this->addSql('DROP TABLE __temp__todo');
    }
}
