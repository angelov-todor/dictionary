<?php declare(strict_types=1);

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180116070751 extends AbstractMigration
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
        $this->addSql('CREATE TABLE units (id CHAR(36) NOT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE units_categories (unit_id CHAR(36) NOT NULL, category_id CHAR(36) NOT NULL, INDEX IDX_DFE44A4FF8BD700D (unit_id), INDEX IDX_DFE44A4F12469DE2 (category_id), PRIMARY KEY(unit_id, category_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit_images (id CHAR(36) NOT NULL, image_id INT(11) DEFAULT NULL, unit_id CHAR(36) DEFAULT NULL, position JSON NOT NULL, INDEX IDX_7746559C3DA5256D (image_id), INDEX IDX_7746559CF8BD700D (unit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id CHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE units_categories ADD CONSTRAINT FK_DFE44A4FF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
        $this->addSql('ALTER TABLE units_categories ADD CONSTRAINT FK_DFE44A4F12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE unit_images ADD CONSTRAINT FK_7746559C3DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('ALTER TABLE unit_images ADD CONSTRAINT FK_7746559CF8BD700D FOREIGN KEY (unit_id) REFERENCES units (id)');
    }

    /**
     * @param Schema $schema
     * @throws \Doctrine\DBAL\Migrations\AbortMigrationException
     */
    public function down(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );
        $this->addSql('ALTER TABLE units_categories DROP FOREIGN KEY FK_DFE44A4FF8BD700D');
        $this->addSql('ALTER TABLE unit_images DROP FOREIGN KEY FK_7746559CF8BD700D');
        $this->addSql('ALTER TABLE units_categories DROP FOREIGN KEY FK_DFE44A4F12469DE2');
        $this->addSql('DROP TABLE units');
        $this->addSql('DROP TABLE units_categories');
        $this->addSql('DROP TABLE unit_images');
        $this->addSql('DROP TABLE categories');
    }
}
