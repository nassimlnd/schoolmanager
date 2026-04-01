<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123201412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_group_file ADD step_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project_group_file ADD file_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_group_file ADD description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE project_group_file ADD CONSTRAINT FK_BD63165273B21E9C FOREIGN KEY (step_id) REFERENCES project_step (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BD63165273B21E9C ON project_group_file (step_id)');
        $this->addSql('ALTER TABLE project_step ADD step_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_group_file DROP CONSTRAINT FK_BD63165273B21E9C');
        $this->addSql('DROP INDEX IDX_BD63165273B21E9C');
        $this->addSql('ALTER TABLE project_group_file DROP step_id');
        $this->addSql('ALTER TABLE project_group_file DROP file_id');
        $this->addSql('ALTER TABLE project_group_file DROP description');
        $this->addSql('ALTER TABLE project_step DROP step_id');
    }
}
