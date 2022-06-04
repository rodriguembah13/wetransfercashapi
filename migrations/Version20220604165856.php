<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220604165856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, zone_id INT DEFAULT NULL, flag VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, INDEX IDX_5373C9669F2C3FAB (zone_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, beneficiare_id INT DEFAULT NULL, montant DOUBLE PRECISION DEFAULT NULL, modetransfert VARCHAR(255) DEFAULT NULL, branche VARCHAR(255) DEFAULT NULL, typeservice VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, prestateurservice VARCHAR(255) DEFAULT NULL, INDEX IDX_723705D19395C3F3 (customer_id), INDEX IDX_723705D1F6054787 (beneficiare_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE zone (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C9669F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1F6054787 FOREIGN KEY (beneficiare_id) REFERENCES contactcustomer (id)');
        $this->addSql('ALTER TABLE contactcustomer ADD customer_id INT DEFAULT NULL, ADD bankiban VARCHAR(255) DEFAULT NULL, ADD bankifsccode VARCHAR(255) DEFAULT NULL, ADD bankswiftcode VARCHAR(255) DEFAULT NULL, ADD bankrelaction VARCHAR(255) DEFAULT NULL, ADD banknationalite VARCHAR(255) DEFAULT NULL, ADD bankbranchnumber VARCHAR(255) DEFAULT NULL, ADD banksignature VARCHAR(255) DEFAULT NULL, ADD bankadressephysique VARCHAR(255) DEFAULT NULL, CHANGE dwollaid phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE contactcustomer ADD CONSTRAINT FK_6F5E4EF59395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('CREATE INDEX IDX_6F5E4EF59395C3F3 ON contactcustomer (customer_id)');
        $this->addSql('ALTER TABLE customer ADD motif VARCHAR(255) DEFAULT NULL, ADD typeidentification VARCHAR(255) DEFAULT NULL, ADD numeropiece VARCHAR(255) DEFAULT NULL, ADD isverify TINYINT(1) DEFAULT NULL, CHANGE dwollaid nationalite VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tauxechange ADD zone_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tauxechange ADD CONSTRAINT FK_F6C3CFAD9F2C3FAB FOREIGN KEY (zone_id) REFERENCES zone (id)');
        $this->addSql('CREATE INDEX IDX_F6C3CFAD9F2C3FAB ON tauxechange (zone_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C9669F2C3FAB');
        $this->addSql('ALTER TABLE tauxechange DROP FOREIGN KEY FK_F6C3CFAD9F2C3FAB');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE zone');
        $this->addSql('ALTER TABLE contactcustomer DROP FOREIGN KEY FK_6F5E4EF59395C3F3');
        $this->addSql('DROP INDEX IDX_6F5E4EF59395C3F3 ON contactcustomer');
        $this->addSql('ALTER TABLE contactcustomer ADD dwollaid VARCHAR(255) DEFAULT NULL, DROP customer_id, DROP phone, DROP bankiban, DROP bankifsccode, DROP bankswiftcode, DROP bankrelaction, DROP banknationalite, DROP bankbranchnumber, DROP banksignature, DROP bankadressephysique');
        $this->addSql('ALTER TABLE customer ADD dwollaid VARCHAR(255) DEFAULT NULL, DROP nationalite, DROP motif, DROP typeidentification, DROP numeropiece, DROP isverify');
        $this->addSql('DROP INDEX IDX_F6C3CFAD9F2C3FAB ON tauxechange');
        $this->addSql('ALTER TABLE tauxechange DROP zone_id');
    }
}
