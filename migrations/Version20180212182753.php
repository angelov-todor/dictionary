<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180212182753 extends AbstractMigration
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

        $this->addSql('CREATE TABLE cognitive_skill_types (cognitive_skill_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                            cognitive_type_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                            INDEX IDX_A0B5E531E5EC612A (cognitive_skill_id),
                            INDEX IDX_A0B5E5317C74C6E9 (cognitive_type_id),
                            PRIMARY KEY(cognitive_skill_id, cognitive_type_id))
                            DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cognitive_skill_types ADD CONSTRAINT FK_A0B5E531E5EC612A FOREIGN KEY (cognitive_skill_id) REFERENCES cognitive_skills (id)');
        $this->addSql('ALTER TABLE cognitive_skill_types ADD CONSTRAINT FK_A0B5E5317C74C6E9 FOREIGN KEY (cognitive_type_id) REFERENCES cognitive_types (id)');
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

        $this->addSql('DROP TABLE cognitive_skill_types');
    }
}
