<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229114914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE character_episode (character_id INT NOT NULL, episode_id INT NOT NULL, PRIMARY KEY(character_id, episode_id))');
        $this->addSql('CREATE INDEX IDX_B40F9CE71136BE75 ON character_episode (character_id)');
        $this->addSql('CREATE INDEX IDX_B40F9CE7362B62A0 ON character_episode (episode_id)');
        $this->addSql('CREATE TABLE episode_character (episode_id INT NOT NULL, character_id INT NOT NULL, PRIMARY KEY(episode_id, character_id))');
        $this->addSql('CREATE INDEX IDX_2DB8260D362B62A0 ON episode_character (episode_id)');
        $this->addSql('CREATE INDEX IDX_2DB8260D1136BE75 ON episode_character (character_id)');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT FK_B40F9CE71136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT FK_B40F9CE7362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE episode_character ADD CONSTRAINT FK_2DB8260D1136BE75 FOREIGN KEY (character_id) REFERENCES character (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT FK_B40F9CE71136BE75');
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT FK_B40F9CE7362B62A0');
        $this->addSql('ALTER TABLE episode_character DROP CONSTRAINT FK_2DB8260D362B62A0');
        $this->addSql('ALTER TABLE episode_character DROP CONSTRAINT FK_2DB8260D1136BE75');
        $this->addSql('DROP TABLE character_episode');
        $this->addSql('DROP TABLE episode_character');
    }
}
