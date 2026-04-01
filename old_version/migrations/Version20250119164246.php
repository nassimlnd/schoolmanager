<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119164246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course ALTER coefficient DROP NOT NULL');
        $this->addSql('ALTER TABLE course ALTER ects DROP NOT NULL');
        $this->addSql('ALTER TABLE teacher ADD teacher_id INT NOT NULL');
        $this->addSql('ALTER TABLE teacher ALTER civility DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE course ALTER coefficient SET NOT NULL');
        $this->addSql('ALTER TABLE course ALTER ects SET NOT NULL');
        $this->addSql('ALTER TABLE teacher DROP teacher_id');
        $this->addSql('ALTER TABLE teacher ALTER civility SET NOT NULL');
    }
}
