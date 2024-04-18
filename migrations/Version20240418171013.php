<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418171013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE course_member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE member_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE course_member (id INT NOT NULL, course_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEDC3B35591CC992 ON course_member (course_id)');
        $this->addSql('CREATE TABLE member_message (id INT NOT NULL, member_id INT DEFAULT NULL, message TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94987EB7597D3FE ON member_message (member_id)');
        $this->addSql('ALTER TABLE course_member ADD CONSTRAINT FK_DEDC3B35591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member_message ADD CONSTRAINT FK_94987EB7597D3FE FOREIGN KEY (member_id) REFERENCES course_member (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE course_member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE member_message_id_seq CASCADE');
        $this->addSql('ALTER TABLE course_member DROP CONSTRAINT FK_DEDC3B35591CC992');
        $this->addSql('ALTER TABLE member_message DROP CONSTRAINT FK_94987EB7597D3FE');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE course_member');
        $this->addSql('DROP TABLE member_message');
    }
}
