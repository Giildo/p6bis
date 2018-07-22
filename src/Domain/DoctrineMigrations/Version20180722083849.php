<?php declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180722083849 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p6bis_picture ADD trick_slug VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE p6bis_picture ADD CONSTRAINT FK_A541272CD62EA8D2 FOREIGN KEY (trick_slug) REFERENCES p6bis_trick (slug)');
        $this->addSql('CREATE INDEX IDX_A541272CD62EA8D2 ON p6bis_picture (trick_slug)');
        $this->addSql('ALTER TABLE p6bis_trick DROP FOREIGN KEY FK_73F32D45C55AF83D');
        $this->addSql('DROP INDEX UNIQ_73F32D45C55AF83D ON p6bis_trick');
        $this->addSql('ALTER TABLE p6bis_trick DROP head_picture_name');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p6bis_picture DROP FOREIGN KEY FK_A541272CD62EA8D2');
        $this->addSql('DROP INDEX IDX_A541272CD62EA8D2 ON p6bis_picture');
        $this->addSql('ALTER TABLE p6bis_picture DROP trick_slug');
        $this->addSql('ALTER TABLE p6bis_trick ADD head_picture_name VARCHAR(50) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE p6bis_trick ADD CONSTRAINT FK_73F32D45C55AF83D FOREIGN KEY (head_picture_name) REFERENCES p6bis_picture (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73F32D45C55AF83D ON p6bis_trick (head_picture_name)');
    }
}
