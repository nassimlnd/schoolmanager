<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119165137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_student (project_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(project_id, student_id))');
        $this->addSql('CREATE INDEX IDX_213DA356166D1F9C ON project_student (project_id)');
        $this->addSql('CREATE INDEX IDX_213DA356CB944F1A ON project_student (student_id)');
        $this->addSql('ALTER TABLE project_student ADD CONSTRAINT FK_213DA356166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_student ADD CONSTRAINT FK_213DA356CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_student DROP CONSTRAINT FK_213DA356166D1F9C');
        $this->addSql('ALTER TABLE project_student DROP CONSTRAINT FK_213DA356CB944F1A');
        $this->addSql('DROP TABLE project_student');
    }
}
