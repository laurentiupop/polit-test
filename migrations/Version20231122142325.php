<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122142325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mep (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, political_group VARCHAR(255) NOT NULL, mep_id INTEGER NOT NULL, national_political_group VARCHAR(255) NOT NULL)');
        $this->addSql('DROP TABLE mpe');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mpe (id INTEGER NOT NULL, full_name CLOB NOT NULL COLLATE "BINARY", country CLOB NOT NULL COLLATE "BINARY", political_group CLOB NOT NULL COLLATE "BINARY", national_political_group CLOB NOT NULL COLLATE "BINARY", mep_id INTEGER NOT NULL)');
        $this->addSql('DROP TABLE mep');
    }
}
