<?php

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171116144945 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, event_body LONGTEXT NOT NULL, type_name VARCHAR(255) NOT NULL, occurred_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, email_verified TINYINT(1) NOT NULL, timezone VARCHAR(255) DEFAULT NULL, locale VARCHAR(255) DEFAULT NULL, currency VARCHAR(20) DEFAULT \'EUR\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_reset_passwords (id VARCHAR(255) NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', expires_at DATETIME NOT NULL, reset_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX id_user_index (id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE derivative_form');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE derivative_form (id BIGINT AUTO_INCREMENT NOT NULL, name VARCHAR(100) DEFAULT NULL COLLATE utf8_general_ci, name_broken VARCHAR(120) DEFAULT NULL COLLATE utf8_general_ci, name_condensed VARCHAR(80) DEFAULT NULL COLLATE utf8_general_ci, description VARCHAR(150) DEFAULT NULL COLLATE utf8_general_ci, is_infinitive TINYINT(1) DEFAULT \'0\', base_word_id INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE users_reset_passwords');
    }
}
