<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180201210603 extends AbstractMigration
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

        $this->addSql('CREATE TABLE cognitive_skills (id CHAR(36) NOT NULL, name VARCHAR(500) NOT NULL,
                            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');

        $this->addSql('ALTER TABLE tests ADD cognitive_skill_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE tests ADD CONSTRAINT FK_1260FC5EE5EC612A FOREIGN KEY (cognitive_skill_id) REFERENCES cognitive_skills (id)');
        $this->addSql('CREATE INDEX IDX_1260FC5EE5EC612A ON tests (cognitive_skill_id)');
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

        $this->addSql('ALTER TABLE tests DROP FOREIGN KEY FK_1260FC5EE5EC612A');
        $this->addSql('DROP INDEX IDX_1260FC5EE5EC612A ON tests');
        $this->addSql('ALTER TABLE tests DROP cognitive_skill_id');
        $this->addSql('DROP TABLE cognitive_skills');
    }
}
