<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250120150233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_group (id SERIAL NOT NULL, project_id INT NOT NULL, group_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7E954D5B166D1F9C ON project_group (project_id)');
        $this->addSql('CREATE TABLE project_group_student (project_group_id INT NOT NULL, student_id INT NOT NULL, PRIMARY KEY(project_group_id, student_id))');
        $this->addSql('CREATE INDEX IDX_E5B26C39C31A529C ON project_group_student (project_group_id)');
        $this->addSql('CREATE INDEX IDX_E5B26C39CB944F1A ON project_group_student (student_id)');
        $this->addSql('CREATE TABLE project_log (id SERIAL NOT NULL, student_id INT NOT NULL, project_id INT NOT NULL, project_log_id INT NOT NULL, description VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, action_type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1D44B226CB944F1A ON project_log (student_id)');
        $this->addSql('CREATE INDEX IDX_1D44B226166D1F9C ON project_log (project_id)');
        $this->addSql('COMMENT ON COLUMN project_log.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE project_group ADD CONSTRAINT FK_7E954D5B166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_group_student ADD CONSTRAINT FK_E5B26C39C31A529C FOREIGN KEY (project_group_id) REFERENCES project_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_group_student ADD CONSTRAINT FK_E5B26C39CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_log ADD CONSTRAINT FK_1D44B226CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_log ADD CONSTRAINT FK_1D44B226166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE project_group DROP CONSTRAINT FK_7E954D5B166D1F9C');
        $this->addSql('ALTER TABLE project_group_student DROP CONSTRAINT FK_E5B26C39C31A529C');
        $this->addSql('ALTER TABLE project_group_student DROP CONSTRAINT FK_E5B26C39CB944F1A');
        $this->addSql('ALTER TABLE project_log DROP CONSTRAINT FK_1D44B226CB944F1A');
        $this->addSql('ALTER TABLE project_log DROP CONSTRAINT FK_1D44B226166D1F9C');
        $this->addSql('DROP TABLE project_group');
        $this->addSql('DROP TABLE project_group_student');
        $this->addSql('DROP TABLE project_log');
    }
}
