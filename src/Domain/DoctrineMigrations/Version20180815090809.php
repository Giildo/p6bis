<?php declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180815090809 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE p6bis_comment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', trick_slug VARCHAR(50) NOT NULL, author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', comment LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_27EE3AC9D62EA8D2 (trick_slug), INDEX IDX_27EE3AC9F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE p6bis_comment ADD CONSTRAINT FK_27EE3AC9D62EA8D2 FOREIGN KEY (trick_slug) REFERENCES p6bis_trick (slug)');
        $this->addSql('ALTER TABLE p6bis_comment ADD CONSTRAINT FK_27EE3AC9F675F31B FOREIGN KEY (author_id) REFERENCES p6bis_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE p6bis_comment');
    }
}
