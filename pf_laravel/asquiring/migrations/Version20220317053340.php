<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317053340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_base (id UUID NOT NULL, order_id UUID NOT NULL, unit_id VARCHAR(50) NOT NULL, product VARCHAR(50) NOT NULL, amount NUMERIC(15, 2) NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E13769158D9F6D38 ON payment_base (order_id)');
        $this->addSql('COMMENT ON COLUMN payment_base.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payment_base.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE payment_base ADD CONSTRAINT FK_E13769158D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP INDEX orders_product_idx');
        $this->addSql('DROP INDEX orders_policy_idx');
        $this->addSql('ALTER TABLE orders ADD ucs_bill VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD ucs_created_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP policy_id');
        $this->addSql('ALTER TABLE orders DROP product');
        $this->addSql('CREATE INDEX orders_ucs_bill_idx ON orders (ucs_bill)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE payment_base');
        $this->addSql('DROP INDEX orders_ucs_bill_idx');
        $this->addSql('ALTER TABLE orders ADD policy_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD product VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE orders DROP ucs_bill');
        $this->addSql('ALTER TABLE orders DROP ucs_created_at');
        $this->addSql('CREATE INDEX orders_product_idx ON orders (product)');
        $this->addSql('CREATE INDEX orders_policy_idx ON orders (policy_id)');
    }
}
