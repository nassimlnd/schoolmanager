<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116221132 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD ine VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD zip_code INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD phone_number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD personal_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD birthplace VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD birth_country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD city VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD nationality VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE student ADD gender VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student DROP ine');
        $this->addSql('ALTER TABLE student DROP address');
        $this->addSql('ALTER TABLE student DROP zip_code');
        $this->addSql('ALTER TABLE student DROP country');
        $this->addSql('ALTER TABLE student DROP phone_number');
        $this->addSql('ALTER TABLE student DROP email');
        $this->addSql('ALTER TABLE student DROP personal_email');
        $this->addSql('ALTER TABLE student DROP birthplace');
        $this->addSql('ALTER TABLE student DROP birth_country');
        $this->addSql('ALTER TABLE student DROP city');
        $this->addSql('ALTER TABLE student DROP nationality');
        $this->addSql('ALTER TABLE student DROP gender');
    }
}
