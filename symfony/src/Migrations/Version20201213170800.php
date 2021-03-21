<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201213170800 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commune ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commune ADD CONSTRAINT FK_E2E2D1EE727ACA70 FOREIGN KEY (parent_id) REFERENCES commune (id)');
        $this->addSql('CREATE INDEX IDX_E2E2D1EE727ACA70 ON commune (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE commune DROP FOREIGN KEY FK_E2E2D1EE727ACA70');
        $this->addSql('DROP INDEX IDX_E2E2D1EE727ACA70 ON commune');
        $this->addSql('ALTER TABLE commune DROP parent_id');
    }
}
