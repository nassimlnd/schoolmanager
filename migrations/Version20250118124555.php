<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118124555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (
          id SERIAL NOT NULL,
          teacher_id INT DEFAULT NULL,
          name VARCHAR(255) NOT NULL,
          rc_id INT NOT NULL,
          coefficient DOUBLE PRECISION NOT NULL,
          ects DOUBLE PRECISION NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_169E6FB941807E1D ON course (teacher_id)');
        $this->addSql('CREATE TABLE grade (
          id SERIAL NOT NULL,
          course_id INT NOT NULL,
          student_id INT NOT NULL,
          grade DOUBLE PRECISION DEFAULT NULL,
          letter_mark VARCHAR(255) DEFAULT NULL,
          trimester INT NOT NULL,
          trimester_name VARCHAR(255) NOT NULL,
          year INT NOT NULL,
          absences INT NOT NULL,
          lates INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_595AAE34591CC992 ON grade (course_id)');
        $this->addSql('CREATE INDEX IDX_595AAE34CB944F1A ON grade (student_id)');
        $this->addSql('CREATE TABLE teacher (
          id SERIAL NOT NULL,
          civility VARCHAR(255) NOT NULL,
          first_name VARCHAR(255) NOT NULL,
          last_name VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('ALTER TABLE
          course
        ADD
          CONSTRAINT FK_169E6FB941807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          grade
        ADD
          CONSTRAINT FK_595AAE34591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          grade
        ADD
          CONSTRAINT FK_595AAE34CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course DROP CONSTRAINT FK_169E6FB941807E1D');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE34591CC992');
        $this->addSql('ALTER TABLE grade DROP CONSTRAINT FK_595AAE34CB944F1A');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE grade');
        $this->addSql('DROP TABLE teacher');
    }
}
