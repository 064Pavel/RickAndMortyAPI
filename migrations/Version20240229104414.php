<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229104414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character DROP CONSTRAINT fk_937ab034c23e42b3');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT fk_937ab034918db72');
        $this->addSql('DROP INDEX idx_937ab034918db72');
        $this->addSql('DROP INDEX idx_937ab034c23e42b3');
        $this->addSql('ALTER TABLE character ADD origin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character DROP origin_id_id');
        $this->addSql('ALTER TABLE character DROP location_id_id');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03456A273CC FOREIGN KEY (origin_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT FK_937AB03464D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_937AB03456A273CC ON character (origin_id)');
        $this->addSql('CREATE INDEX IDX_937AB03464D218E ON character (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03456A273CC');
        $this->addSql('ALTER TABLE character DROP CONSTRAINT FK_937AB03464D218E');
        $this->addSql('DROP INDEX IDX_937AB03456A273CC');
        $this->addSql('DROP INDEX IDX_937AB03464D218E');
        $this->addSql('ALTER TABLE character ADD origin_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character ADD location_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE character DROP origin_id');
        $this->addSql('ALTER TABLE character DROP location_id');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT fk_937ab034c23e42b3 FOREIGN KEY (origin_id_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE character ADD CONSTRAINT fk_937ab034918db72 FOREIGN KEY (location_id_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_937ab034918db72 ON character (location_id_id)');
        $this->addSql('CREATE INDEX idx_937ab034c23e42b3 ON character (origin_id_id)');
    }
}
