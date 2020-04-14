<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200414192045 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE heart (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_60ECC3E6F624B39D (sender_id), INDEX IDX_60ECC3E6E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, recipient_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FE92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, user_gallery_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_16DB4F89A3806963 (user_gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, reporter_id INT DEFAULT NULL, reported_message_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_C42F7784E1CFE6F5 (reporter_id), INDEX IDX_C42F7784387BD835 (reported_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birth_date DATE NOT NULL, gender SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, api_token VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), INDEX IDX_8D93D6498BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE visit (id INT AUTO_INCREMENT NOT NULL, visitor_id INT DEFAULT NULL, visited_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_437EE93970BEE6D (visitor_id), INDEX IDX_437EE93938C9FE8F (visited_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE heart ADD CONSTRAINT FK_60ECC3E6F624B39D FOREIGN KEY (sender_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE heart ADD CONSTRAINT FK_60ECC3E6E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89A3806963 FOREIGN KEY (user_gallery_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784E1CFE6F5 FOREIGN KEY (reporter_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report ADD CONSTRAINT FK_C42F7784387BD835 FOREIGN KEY (reported_message_id) REFERENCES message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93970BEE6D FOREIGN KEY (visitor_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE visit ADD CONSTRAINT FK_437EE93938C9FE8F FOREIGN KEY (visited_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784387BD835');
        $this->addSql('ALTER TABLE heart DROP FOREIGN KEY FK_60ECC3E6F624B39D');
        $this->addSql('ALTER TABLE heart DROP FOREIGN KEY FK_60ECC3E6E92F8F78');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89A3806963');
        $this->addSql('ALTER TABLE report DROP FOREIGN KEY FK_C42F7784E1CFE6F5');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE93970BEE6D');
        $this->addSql('ALTER TABLE visit DROP FOREIGN KEY FK_437EE93938C9FE8F');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE heart');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE visit');
    }
}
