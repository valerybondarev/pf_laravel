<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211228100005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id UUID NOT NULL, policy_id VARCHAR(50) NOT NULL, product VARCHAR(50) NOT NULL, agent_id VARCHAR(50) DEFAULT NULL, client_id VARCHAR(50) DEFAULT NULL, source VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, amount DOUBLE PRECISION NOT NULL, status VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX orders_policy_idx ON orders (policy_id)');
        $this->addSql('CREATE INDEX orders_product_idx ON orders (product)');
        $this->addSql('CREATE INDEX orders_status_idx ON orders (status)');
        $this->addSql('CREATE INDEX orders_source_idx ON orders (source)');
        $this->addSql('CREATE INDEX orders_created_at_idx ON orders (created_at)');
        $this->addSql('COMMENT ON COLUMN orders.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE payments (id UUID NOT NULL, order_id UUID NOT NULL, provider VARCHAR(20) NOT NULL, type VARCHAR(20) NOT NULL, payed_type VARCHAR(20) DEFAULT NULL, device_type VARCHAR(50) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, invoice_id VARCHAR(50) DEFAULT NULL, qr_id VARCHAR(50) DEFAULT NULL, payment_url VARCHAR(255) DEFAULT NULL, paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, paid_status VARCHAR(50) DEFAULT NULL, transaction_id INT DEFAULT NULL, verified_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, verify_status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX payments_order_idx ON payments (order_id)');
        $this->addSql('COMMENT ON COLUMN payments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments.order_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE payments_history (id UUID NOT NULL, payment_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, payload JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX payments_history_payment_idx ON payments_history (payment_id)');
        $this->addSql('COMMENT ON COLUMN payments_history.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments_history.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE payments_request (id UUID NOT NULL, payment_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, method VARCHAR(10) NOT NULL, url VARCHAR(255) NOT NULL, request JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX payments_request_payment_idx ON payments_request (payment_id)');
        $this->addSql('COMMENT ON COLUMN payments_request.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments_request.payment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE payments_response (id UUID NOT NULL, request_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, success BOOLEAN NOT NULL, response JSON NOT NULL, error_code VARCHAR(10) DEFAULT NULL, error_message VARCHAR(512) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX payments_response_request_idx ON payments_response (request_id)');
        $this->addSql('COMMENT ON COLUMN payments_response.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN payments_response.request_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE visited (id UUID NOT NULL, order_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, visited_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX visited_order_idx ON visited (order_id)');
        $this->addSql('COMMENT ON COLUMN visited.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN visited.order_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE payments');
        $this->addSql('DROP TABLE payments_history');
        $this->addSql('DROP TABLE payments_request');
        $this->addSql('DROP TABLE payments_response');
        $this->addSql('DROP TABLE visited');
    }
}
