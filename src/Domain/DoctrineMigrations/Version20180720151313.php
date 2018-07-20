<?php declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180720151313 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE p6bis_trick (slug VARCHAR(50) NOT NULL, category_slug VARCHAR(50) NOT NULL, author_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', head_picture_name VARCHAR(50) DEFAULT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, published TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_73F32D451306E125 (category_slug), INDEX IDX_73F32D45F675F31B (author_id), UNIQUE INDEX UNIQ_73F32D45C55AF83D (head_picture_name), PRIMARY KEY(slug)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D451306E125 FOREIGN KEY (category_slug) REFERENCES p6bis_category (slug)');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D45F675F31B FOREIGN KEY (author_id) REFERENCES p6bis_user (id)');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D45C55AF83D FOREIGN KEY (head_picture_name) REFERENCES p6bis_picture (name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE p6bis_trick');
    }
}
