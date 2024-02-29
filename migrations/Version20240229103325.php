<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229103325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE character_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE character (id INT NOT NULL, origin_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, species VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_937AB034C23E42B3 ON character (origin_id)');
        $this->addSql('CREATE INDEX IDX_937AB034918DB72 ON character (location_id)');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB034C23E42B3 FOREIGN KEY (origin_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB034918DB72 FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE character_id_seq CASCADE');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB034C23E42B3');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB034918DB72');
        $this->addSql('DROP TABLE character');
    }
}
