<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302212623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character (id INT NOT NULL, origin_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, species VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB03456A273CC ON character (origin_id)');
        $this->addSql('CREATE INDEX IDX_937AB03464D218E ON character (location_id)');
        $this->addSql('CREATE TABLE episode (id INT NOT NULL, name VARCHAR(255) NOT NULL, air_date VARCHAR(255) NOT NULL, episode VARCHAR(255) NOT NULL, views INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE episode_character (id SERIAL PRIMARY KEY, episode_id INT NOT NULL, character_id INT NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2DB8260D362B62A0 ON episode_character (episode_id)');
        $this->addSql('CREATE INDEX IDX_2DB8260D1136BE75 ON episode_character (character_id)');
        $this->addSql('CREATE UNIQUE INDEX character_episode_episode_id_character_id ON episode_character (episode_id, character_id)');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, dimension VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03456A273CC FOREIGN KEY (origin_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03464D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03456A273CC');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03464D218E');
        $this->addSql('ALTER TABLE episode_character DROP CONSTRAINT FK_2DB8260D362B62A0');
        $this->addSql('ALTER TABLE episode_character DROP CONSTRAINT FK_2DB8260D1136BE75');
        $this->addSql('DROP TABLE character');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE episode_character');
        $this->addSql('DROP TABLE location');
    }
}
