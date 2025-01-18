<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118162523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classe (
          id SERIAL NOT NULL,
          name VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          school VARCHAR(255) NOT NULL,
          year INT NOT NULL,
          trimester INT NOT NULL,
          puid INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('ALTER TABLE student ADD classe_id INT NOT NULL');
        $this->addSql('ALTER TABLE
          student
        ADD
          CONSTRAINT FK_B723AF338F5EA509 FOREIGN KEY (classe_id) REFERENCES classe (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B723AF338F5EA509 ON student (classe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE student DROP CONSTRAINT FK_B723AF338F5EA509');
        $this->addSql('DROP TABLE classe');
        $this->addSql('DROP INDEX IDX_B723AF338F5EA509');
        $this->addSql('ALTER TABLE student DROP classe_id');
    }
}
