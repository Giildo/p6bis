<?php declare(strict_types=1);

namespace App\Domain\DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180903200226 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p6bis_user ADD picture_name VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE p6bis_user ADD CONSTRAINT FK_5F2AD466E1C3FB93 FOREIGN KEY (picture_name) REFERENCES p6bis_picture (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F2AD466E1C3FB93 ON p6bis_user (picture_name)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE p6bis_user DROP FOREIGN KEY FK_5F2AD466E1C3FB93');
        $this->addSql('DROP INDEX UNIQ_5F2AD466E1C3FB93 ON p6bis_user');
        $this->addSql('ALTER TABLE p6bis_user DROP picture_name');
    }
}
