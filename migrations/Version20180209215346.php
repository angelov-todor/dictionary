<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180209215346 extends AbstractMigration
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

        $this->addSql('CREATE TABLE answers (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\',
                            test_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                            unit_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                            user_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\',
                            answer JSON NOT NULL,
                            occurred_at DATETIME NOT NULL,
                            INDEX IDX_50D0C6061E5D0459 (test_id),
                            INDEX IDX_50D0C606F8BD700D (unit_id),
                            INDEX IDX_50D0C606A76ED395 (user_id),
                            PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E5D0459 FOREIGN KEY (test_id) REFERENCES tests (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C606A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
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

        $this->addSql('DROP TABLE answers');
    }
}
