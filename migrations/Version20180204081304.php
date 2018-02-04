<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180204081304 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE images ADD creator_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                            ADD created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A61220EA6 ON images (creator_id)');
        $this->addSql('ALTER TABLE units ADD name VARCHAR(255) NOT NULL,
                            ADD type VARCHAR(20) NOT NULL,
                            ADD time_to_conduct INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users DROP currency');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema)
    {
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A61220EA6');
        $this->addSql('DROP INDEX IDX_E01FBE6A61220EA6 ON images');
        $this->addSql('ALTER TABLE images DROP creator_id, DROP created_at');
        $this->addSql('ALTER TABLE units DROP name, DROP type, DROP time_to_conduct');
        $this->addSql('ALTER TABLE users ADD currency VARCHAR(20) DEFAULT \'EUR\' NOT NULL COLLATE utf8_unicode_ci');
    }
}
