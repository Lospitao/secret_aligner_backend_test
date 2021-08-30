<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Exception\MigrationException;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830073558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'INSERT admin user with email myphpisland@gmail . com and password 123456';
    }
    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO `user` (`id`,`roles`, `password`,`email`) VALUES (19, \'[ "ROLE_PLAYER", "ROLE_ADMIN" ]\', \'$argon2id$v=19$m=65536,t=4,p=1$M3DEOJGhq0vOwnE0AhOqDg$FalWnm7BuXgx9zzMZNWkpwy0OPk6bckIqiq+Uy08CQM\', \'myphpisland@gmail.com\')');
    }
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}