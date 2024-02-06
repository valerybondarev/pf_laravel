<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418111311 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD contractor JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD contractor_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD contractor_inn VARCHAR(255) DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN orders.contractor IS \'(DC2Type:json_document)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP contractor');
        $this->addSql('ALTER TABLE orders DROP contractor_name');
        $this->addSql('ALTER TABLE orders DROP contractor_inn');
    }
}
