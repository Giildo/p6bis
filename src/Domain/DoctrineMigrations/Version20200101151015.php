<?php

declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200101151015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE p6bis_category (slug VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(slug))');
        $this->addSql('CREATE TABLE p6bis_comment (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , comment CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, trick_slug VARCHAR(50) NOT NULL, author_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE p6bis_picture (name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, extension VARCHAR(5) NOT NULL, head_picture BOOLEAN NOT NULL, trick_slug VARCHAR(50) DEFAULT NULL, PRIMARY KEY(name))');
        $this->addSql('CREATE TABLE p6bis_trick (slug VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, description CLOB NOT NULL, published BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, category_slug VARCHAR(50) NOT NULL, author_id CHAR(36) NOT NULL --(DC2Type:uuid)
        , PRIMARY KEY(slug))');
        $this->addSql('CREATE TABLE p6bis_user (id CHAR(36) NOT NULL --(DC2Type:uuid)
        , username VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, mail VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles CLOB NOT NULL --(DC2Type:array)
        , token VARCHAR(43) DEFAULT NULL, token_date DATETIME DEFAULT NULL, picture_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2AD466E1C3FB93 ON p6bis_user (picture_name)');
        $this->addSql('CREATE TABLE p6bis_video (name VARCHAR(20) NOT NULL, trick_slug VARCHAR(50) NOT NULL, PRIMARY KEY(name))');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE p6bis_category');
        $this->addSql('DROP TABLE p6bis_comment');
        $this->addSql('DROP TABLE p6bis_picture');
        $this->addSql('DROP TABLE p6bis_trick');
        $this->addSql('DROP TABLE p6bis_user');
        $this->addSql('DROP TABLE p6bis_video');
    }
}
