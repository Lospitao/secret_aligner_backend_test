<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210830094605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Describe User Roles';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO `user_role` (`id`, `name`) VALUES (1,\'USER\' )');
        $this->addSql('INSERT INTO `user_role` (`id`, `name`) VALUES (2,\'ADMIN\' )');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
