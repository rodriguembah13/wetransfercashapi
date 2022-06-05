<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220605055952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD fraisenvoi DOUBLE PRECISION DEFAULT NULL, ADD montanttotal DOUBLE PRECISION DEFAULT NULL, ADD datetransaction DATETIME DEFAULT NULL, ADD raisontransaction LONGTEXT DEFAULT NULL, ADD numerotransaction VARCHAR(255) DEFAULT NULL, ADD typetransaction VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction DROP fraisenvoi, DROP montanttotal, DROP datetransaction, DROP raisontransaction, DROP numerotransaction, DROP typetransaction');
    }
}
