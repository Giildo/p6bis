<?php

declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418120559 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE p6bis_category (slug VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(slug))');
        $this->addSql('CREATE TABLE p6bis_comment (id UUID NOT NULL, trick_slug VARCHAR(50) NOT NULL, author_id UUID NOT NULL, comment TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_27EE3AC9D62EA8D2 ON p6bis_comment (trick_slug)');
        $this->addSql('CREATE INDEX IDX_27EE3AC9F675F31B ON p6bis_comment (author_id)');
        $this->addSql('COMMENT ON COLUMN p6bis_comment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN p6bis_comment.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE p6bis_picture (name VARCHAR(50) NOT NULL, trick_slug VARCHAR(50) DEFAULT NULL, description VARCHAR(255) NOT NULL, extension VARCHAR(5) NOT NULL, head_picture BOOLEAN NOT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE INDEX IDX_A541272CD62EA8D2 ON p6bis_picture (trick_slug)');
        $this->addSql('CREATE TABLE p6bis_trick (slug VARCHAR(50) NOT NULL, category_slug VARCHAR(50) NOT NULL, author_id UUID NOT NULL, name VARCHAR(50) NOT NULL, description TEXT NOT NULL, published BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(slug))');
        $this->addSql('CREATE INDEX IDX_73F32D451306E125 ON p6bis_trick (category_slug)');
        $this->addSql('CREATE INDEX IDX_73F32D45F675F31B ON p6bis_trick (author_id)');
        $this->addSql('COMMENT ON COLUMN p6bis_trick.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE p6bis_user (id UUID NOT NULL, picture_name VARCHAR(50) DEFAULT NULL, username VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles TEXT NOT NULL, token VARCHAR(43) DEFAULT NULL, token_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2AD466E1C3FB93 ON p6bis_user (picture_name)');
        $this->addSql('COMMENT ON COLUMN p6bis_user.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN p6bis_user.roles IS \'(DC2Type:array)\'');
        $this->addSql('CREATE TABLE p6bis_video (name VARCHAR(20) NOT NULL, trick_slug VARCHAR(50) NOT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE INDEX IDX_D7C45E77D62EA8D2 ON p6bis_video (trick_slug)');
        $this->addSql('ALTER TABLE p6bis_comment ADD CONSTRAINT FK_27EE3AC9D62EA8D2 FOREIGN KEY (trick_slug) REFERENCES p6bis_trick (slug) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_comment ADD CONSTRAINT FK_27EE3AC9F675F31B FOREIGN KEY (author_id) REFERENCES p6bis_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_picture ADD CONSTRAINT FK_A541272CD62EA8D2 FOREIGN KEY (trick_slug) REFERENCES p6bis_trick (slug) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D451306E125 FOREIGN KEY (category_slug) REFERENCES p6bis_category (slug) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D45F675F31B FOREIGN KEY (author_id) REFERENCES p6bis_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_user ADD CONSTRAINT FK_5F2AD466E1C3FB93 FOREIGN KEY (picture_name) REFERENCES p6bis_picture (name) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE p6bis_video ADD CONSTRAINT FK_D7C45E77D62EA8D2 FOREIGN KEY (trick_slug) REFERENCES p6bis_trick (slug) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE p6bis_trick DROP CONSTRAINT FK_73F32D451306E125');
        $this->addSql('ALTER TABLE p6bis_user DROP CONSTRAINT FK_5F2AD466E1C3FB93');
        $this->addSql('ALTER TABLE p6bis_comment DROP CONSTRAINT FK_27EE3AC9D62EA8D2');
        $this->addSql('ALTER TABLE p6bis_picture DROP CONSTRAINT FK_A541272CD62EA8D2');
        $this->addSql('ALTER TABLE p6bis_video DROP CONSTRAINT FK_D7C45E77D62EA8D2');
        $this->addSql('ALTER TABLE p6bis_comment DROP CONSTRAINT FK_27EE3AC9F675F31B');
        $this->addSql('ALTER TABLE p6bis_trick DROP CONSTRAINT FK_73F32D45F675F31B');
        $this->addSql('DROP TABLE p6bis_category');
        $this->addSql('DROP TABLE p6bis_comment');
        $this->addSql('DROP TABLE p6bis_picture');
        $this->addSql('DROP TABLE p6bis_trick');
        $this->addSql('DROP TABLE p6bis_user');
        $this->addSql('DROP TABLE p6bis_video');
    }
}
