<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122160314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__mep AS SELECT id, full_name, country, political_group, mep_id, national_political_group, address, phone, email, twitter, instagram, website, facebook, address2 FROM mep');
        $this->addSql('DROP TABLE mep');
        $this->addSql('CREATE TABLE mep (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, political_group VARCHAR(255) NOT NULL, mep_id INTEGER NOT NULL, national_political_group VARCHAR(255) NOT NULL, address VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, twitter VARCHAR(255) NOT NULL, instagram VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, facebook VARCHAR(255) NOT NULL, address2 VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO mep (id, full_name, country, political_group, mep_id, national_political_group, address, phone, email, twitter, instagram, website, facebook, address2) SELECT id, full_name, country, political_group, mep_id, national_political_group, address, phone, email, twitter, instagram, website, facebook, address2 FROM __temp__mep');
        $this->addSql('DROP TABLE __temp__mep');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mep ADD COLUMN city VARCHAR(255) NOT NULL');
    }
}
