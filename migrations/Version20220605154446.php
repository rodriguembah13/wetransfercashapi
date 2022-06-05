<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605154446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grilletarifaire ADD zone_id INT DEFAULT NULL, DROP zone');
        $this->addSql('ALTER TABLE grilletarifaire ADD CONSTRAINT FK_DBEEC5E99F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_DBEEC5E99F2C3FAB ON grilletarifaire (zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grilletarifaire DROP FOREIGN KEY FK_DBEEC5E99F2C3FAB');
        $this->addSql('DROP INDEX IDX_DBEEC5E99F2C3FAB ON grilletarifaire');
        $this->addSql('ALTER TABLE grilletarifaire ADD zone VARCHAR(255) DEFAULT NULL, DROP zone_id');
    }
}
