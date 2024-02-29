<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229115128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT fk_b40f9ce71136be75');
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT fk_b40f9ce7362b62a0');
        $this->addSql('DROP TABLE character_episode');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE character_episode (character_id INT NOT NULL, episode_id INT NOT NULL, PRIMARY KEY(character_id, episode_id))');
        $this->addSql('CREATE INDEX idx_b40f9ce7362b62a0 ON character_episode (episode_id)');
        $this->addSql('CREATE INDEX idx_b40f9ce71136be75 ON character_episode (character_id)');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT fk_b40f9ce71136be75 FOREIGN KEY (character_id) REFERENCES "character" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT fk_b40f9ce7362b62a0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
