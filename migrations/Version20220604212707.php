<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220604212707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD compte_id INT DEFAULT NULL, ADD phone VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F2C56620 FOREIGN KEY (compte_id) REFERENCES `user` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E09F2C56620 ON customer (compte_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F2C56620');
        $this->addSql('DROP INDEX UNIQ_81398E09F2C56620 ON customer');
        $this->addSql('ALTER TABLE customer DROP compte_id, DROP phone, DROP country');
    }
}
