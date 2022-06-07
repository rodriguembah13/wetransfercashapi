<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607070323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD agent_id INT DEFAULT NULL, ADD numeroidentifiant VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D13414710B FOREIGN KEY (agent_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_723705D13414710B ON transaction (agent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D13414710B');
        $this->addSql('DROP INDEX IDX_723705D13414710B ON transaction');
        $this->addSql('ALTER TABLE transaction DROP agent_id, DROP numeroidentifiant');
    }
}
