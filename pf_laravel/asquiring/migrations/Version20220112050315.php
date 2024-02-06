<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112050315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER expires_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER expires_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER amount TYPE NUMERIC(15, 2)');
        $this->addSql('ALTER TABLE orders ALTER amount DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER updated_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER paid_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER paid_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER verified_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER verified_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_history ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments_history ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_request ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments_request ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_response ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE payments_response ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE visited ALTER created_at TYPE TIMESTAMP(0) WITH TIME ZONE');
        $this->addSql('ALTER TABLE visited ALTER created_at DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER expires_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE orders ALTER expires_at DROP DEFAULT');
        $this->addSql('ALTER TABLE orders ALTER amount TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE orders ALTER amount DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER updated_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER paid_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER paid_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments ALTER verified_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments ALTER verified_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_history ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments_history ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_request ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments_request ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE payments_response ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payments_response ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE visited ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE visited ALTER created_at DROP DEFAULT');
    }
}
