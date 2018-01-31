<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180131080746 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );
        $this->addSql('ALTER TABLE units_categories DROP FOREIGN KEY FK_DFE44A4F12469DE2');
        $this->addSql('CREATE TABLE cognitive_types (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', parent_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(500) NOT NULL, INDEX IDX_19BAF148727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tests (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE test_units (test_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', unit_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_E26ED2421E5D0459 (test_id), INDEX IDX_E26ED242F8BD700D (unit_id), PRIMARY KEY(test_id, unit_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cognitive_types ADD CONSTRAINT FK_19BAF148727ACA70 FOREIGN KEY (parent_id) REFERENCES cognitive_types (id)');
        $this->addSql('ALTER TABLE test_units ADD CONSTRAINT FK_E26ED2421E5D0459 FOREIGN KEY (test_id) REFERENCES tests (id)');
        $this->addSql('ALTER TABLE test_units ADD CONSTRAINT FK_E26ED242F8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE units_categories');
        $this->addSql('ALTER TABLE units ADD cognitive_type_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD cognitive_subtype_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B074497C74C6E9 FOREIGN KEY (cognitive_type_id) REFERENCES cognitive_types (id)');
        $this->addSql('ALTER TABLE units ADD CONSTRAINT FK_E9B07449F5DCD672 FOREIGN KEY (cognitive_subtype_id) REFERENCES cognitive_types (id)');
        $this->addSql('CREATE INDEX IDX_E9B074497C74C6E9 ON units (cognitive_type_id)');
        $this->addSql('CREATE INDEX IDX_E9B07449F5DCD672 ON units (cognitive_subtype_id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE cognitive_types DROP FOREIGN KEY FK_19BAF148727ACA70');
        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B074497C74C6E9');
        $this->addSql('ALTER TABLE units DROP FOREIGN KEY FK_E9B07449F5DCD672');
        $this->addSql('ALTER TABLE test_units DROP FOREIGN KEY FK_E26ED2421E5D0459');
        $this->addSql('CREATE TABLE categories (id CHAR(36) NOT NULL COLLATE utf8_unicode_ci, name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE units_categories (unit_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci, category_id CHAR(36) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_DFE44A4FF8BD700D (unit_id), INDEX IDX_DFE44A4F12469DE2 (category_id), PRIMARY KEY(unit_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE units_categories ADD CONSTRAINT FK_DFE44A4F12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE units_categories ADD CONSTRAINT FK_DFE44A4FF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
        $this->addSql('DROP TABLE cognitive_types');
        $this->addSql('DROP TABLE tests');
        $this->addSql('DROP TABLE test_units');
        $this->addSql('DROP INDEX IDX_E9B074497C74C6E9 ON units');
        $this->addSql('DROP INDEX IDX_E9B07449F5DCD672 ON units');
        $this->addSql('ALTER TABLE units DROP cognitive_type_id, DROP cognitive_subtype_id, CHANGE id id CHAR(36) NOT NULL COLLATE utf8_unicode_ci');
    }
}
