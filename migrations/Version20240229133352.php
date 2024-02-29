<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229133352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE episode_character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character_episode (character_id INT NOT NULL, episode_id INT NOT NULL, PRIMARY KEY(character_id, episode_id))');
        $this->addSql('CREATE INDEX IDX_B40F9CE71136BE75 ON character_episode (character_id)');
        $this->addSql('CREATE INDEX IDX_B40F9CE7362B62A0 ON character_episode (episode_id)');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT FK_B40F9CE71136BE75 FOREIGN KEY (character_id) REFERENCES character (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character_episode ADD CONSTRAINT FK_B40F9CE7362B62A0 FOREIGN KEY (episode_id) REFERENCES episode (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE episode_character DROP CONSTRAINT episode_character_pkey');
        $this->addSql('ALTER TABLE episode_character ADD id INT NOT NULL');
        $this->addSql('ALTER TABLE episode_character ALTER episode_id DROP NOT NULL');
        $this->addSql('ALTER TABLE episode_character ALTER character_id DROP NOT NULL');
        $this->addSql('ALTER TABLE episode_character ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE episode_character_id_seq CASCADE');
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT FK_B40F9CE71136BE75');
        $this->addSql('ALTER TABLE character_episode DROP CONSTRAINT FK_B40F9CE7362B62A0');
        $this->addSql('DROP TABLE character_episode');
        $this->addSql('DROP INDEX episode_character_pkey');
        $this->addSql('ALTER TABLE episode_character DROP id');
        $this->addSql('ALTER TABLE episode_character ALTER episode_id SET NOT NULL');
        $this->addSql('ALTER TABLE episode_character ALTER character_id SET NOT NULL');
        $this->addSql('ALTER TABLE episode_character ADD PRIMARY KEY (episode_id, character_id)');
    }
}
