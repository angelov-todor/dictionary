<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180201214938 extends AbstractMigration
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

        $this->addSql('CREATE TABLE methodologies (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(250) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tests ADD methodology_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE tests ADD CONSTRAINT FK_1260FC5ED22DC3B FOREIGN KEY (methodology_id) REFERENCES methodologies (id)');
        $this->addSql('CREATE INDEX IDX_1260FC5ED22DC3B ON tests (methodology_id)');
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

        $this->addSql('ALTER TABLE tests DROP FOREIGN KEY FK_1260FC5ED22DC3B');
        $this->addSql('DROP TABLE methodologies');
        $this->addSql('DROP INDEX IDX_1260FC5ED22DC3B ON tests');
        $this->addSql('ALTER TABLE tests DROP methodology_id');
    }
}
