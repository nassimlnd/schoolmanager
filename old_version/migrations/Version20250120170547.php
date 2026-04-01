<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120170547 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_log ADD project_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_log ADD CONSTRAINT FK_1D44B226C31A529C FOREIGN KEY (project_group_id) REFERENCES project_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1D44B226C31A529C ON project_log (project_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_log DROP CONSTRAINT FK_1D44B226C31A529C');
        $this->addSql('DROP INDEX IDX_1D44B226C31A529C');
        $this->addSql('ALTER TABLE project_log DROP project_group_id');
    }
}
