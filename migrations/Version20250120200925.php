<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120200925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ALTER date_of_birth DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER level DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER ine DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER address DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER zip_code DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER country DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER phone_number DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER personal_email DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER birthplace DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER birth_country DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER nationality DROP NOT NULL');
        $this->addSql('ALTER TABLE student ALTER gender DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student ALTER date_of_birth SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER level SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER ine SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER address SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER zip_code SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER country SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER phone_number SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER personal_email SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER birthplace SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER birth_country SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER nationality SET NOT NULL');
        $this->addSql('ALTER TABLE student ALTER gender SET NOT NULL');
    }
}
