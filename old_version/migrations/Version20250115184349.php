<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115184349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student (
          id SERIAL NOT NULL,
          related_user_id INT NOT NULL,
          student_id VARCHAR(255) NOT NULL,
          first_name VARCHAR(255) NOT NULL,
          last_name VARCHAR(255) NOT NULL,
          date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          level VARCHAR(255) NOT NULL,
          program VARCHAR(255) NOT NULL,
          my_ges_credentials_token VARCHAR(255) DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B723AF3398771930 ON student (related_user_id)');
        $this->addSql('ALTER TABLE
          student
        ADD
          CONSTRAINT FK_B723AF3398771930 FOREIGN KEY (related_user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF3398771930');
        $this->addSql('DROP TABLE student');
    }
}
