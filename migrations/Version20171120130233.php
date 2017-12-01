<?php

namespace migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171120130233 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, event_body LONGTEXT NOT NULL, type_name VARCHAR(255) NOT NULL, occurred_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_reset_passwords (id VARCHAR(255) NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', expires_at DATETIME NOT NULL, reset_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX id_user_index (id, user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD created_at DATETIME NOT NULL DEFAULT NOW(), ADD timezone VARCHAR(255) DEFAULT NULL, ADD locale VARCHAR(255) DEFAULT NULL, ADD currency VARCHAR(20) DEFAULT \'EUR\' NOT NULL, DROP fullname, DROP username, DROP username_canonical, DROP email_canonical, DROP salt, DROP last_login, DROP confirmation_token, DROP password_requested_at, DROP roles, CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE email email VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL, CHANGE enabled email_verified TINYINT(1) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE users_reset_passwords');
        $this->addSql('ALTER TABLE users ADD fullname VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD username VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, ADD username_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, ADD email_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, ADD salt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, ADD last_login DATETIME DEFAULT NULL, ADD confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8_unicode_ci, ADD password_requested_at DATETIME DEFAULT NULL, ADD roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', DROP created_at, DROP timezone, DROP locale, DROP currency, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, CHANGE password password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, CHANGE email_verified enabled TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64992FC23A8 ON users (username_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649A0D96FBF ON users (email_canonical)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C05FB297 ON users (confirmation_token)');
    }
}
