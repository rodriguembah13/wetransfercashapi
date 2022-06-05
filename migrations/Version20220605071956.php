<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605071956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD country_id INT DEFAULT NULL, DROP country');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_81398E09F92F3E70 ON customer (country_id)');
        $this->addSql('ALTER TABLE transaction ADD country_id INT DEFAULT NULL, DROP country');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_723705D1F92F3E70 ON transaction (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09F92F3E70');
        $this->addSql('DROP INDEX IDX_81398E09F92F3E70 ON customer');
        $this->addSql('ALTER TABLE customer ADD country VARCHAR(255) DEFAULT NULL, DROP country_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1F92F3E70');
        $this->addSql('DROP INDEX IDX_723705D1F92F3E70 ON transaction');
        $this->addSql('ALTER TABLE transaction ADD country VARCHAR(255) DEFAULT NULL, DROP country_id');
    }
}
