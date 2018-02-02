<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180202090307 extends AbstractMigration
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

        $this->addSql('ALTER TABLE tests ADD creator_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                            ADD grading_scale VARCHAR(255) NOT NULL,
                            ADD min_age INT DEFAULT NULL,
                            ADD max_age INT DEFAULT NULL,
                            ADD points_required INT DEFAULT NULL,
                            ADD time_to_conduct INT DEFAULT NULL,
                            ADD notes VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE tests ADD CONSTRAINT FK_1260FC5E61220EA6 FOREIGN KEY (creator_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_1260FC5E61220EA6 ON tests (creator_id)');
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

        $this->addSql('ALTER TABLE tests DROP FOREIGN KEY FK_1260FC5E61220EA6');
        $this->addSql('DROP INDEX IDX_1260FC5E61220EA6 ON tests');
        $this->addSql('ALTER TABLE tests DROP creator_id, 
                            DROP grading_scale,
                            DROP min_age,
                            DROP max_age,
                            DROP points_required,
                            DROP time_to_conduct,
                            DROP notes');
    }
}
