<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231114165442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       // $this->addSql('CREATE TABLE gift (id INT AUTO_INCREMENT NOT NULL, gift_list_id INT DEFAULT NULL, reserved_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, purchase_link VARCHAR(255) DEFAULT NULL, is_chosen TINYINT(1) DEFAULT NULL, chosen_by_email VARCHAR(255) DEFAULT NULL, INDEX IDX_A47C990D51F42524 (gift_list_id), INDEX IDX_A47C990DBCDB4AF4 (reserved_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE gift_list (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, password VARCHAR(255) DEFAULT NULL, begin_date DATETIME NOT NULL, end_date DATETIME NOT NULL, theme VARCHAR(255) NOT NULL, INDEX IDX_B6B50A45A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(70) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE personne (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, job_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, firstname VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, age SMALLINT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_FCEC9EFCCFA12B8 (profile_id), INDEX IDX_FCEC9EFBE04EA9 (job_id), INDEX IDX_FCEC9EFB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE personne_hobby (personne_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_2D85E25EA21BD112 (personne_id), INDEX IDX_2D85E25E322B2123 (hobby_id), PRIMARY KEY(personne_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, rs VARCHAR(50) NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, age SMALLINT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        // $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D51F42524 FOREIGN KEY (gift_list_id) REFERENCES gift_list (id)');
        // $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DBCDB4AF4 FOREIGN KEY (reserved_by_id) REFERENCES user (id)');
        // $this->addSql('ALTER TABLE gift_list ADD CONSTRAINT FK_B6B50A45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        // $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        // $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        // $this->addSql('ALTER TABLE personne ADD CONSTRAINT FK_FCEC9EFB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        // $this->addSql('ALTER TABLE personne_hobby ADD CONSTRAINT FK_2D85E25EA21BD112 FOREIGN KEY (personne_id) REFERENCES personne (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE personne_hobby ADD CONSTRAINT FK_2D85E25E322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        // $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D51F42524');
        // $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990DBCDB4AF4');
        // $this->addSql('ALTER TABLE gift_list DROP FOREIGN KEY FK_B6B50A45A76ED395');
        // $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFCCFA12B8');
        // $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFBE04EA9');
        // $this->addSql('ALTER TABLE personne DROP FOREIGN KEY FK_FCEC9EFB03A8386');
        // $this->addSql('ALTER TABLE personne_hobby DROP FOREIGN KEY FK_2D85E25EA21BD112');
        // $this->addSql('ALTER TABLE personne_hobby DROP FOREIGN KEY FK_2D85E25E322B2123');
        // $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        // $this->addSql('DROP TABLE gift');
        // $this->addSql('DROP TABLE gift_list');
        // $this->addSql('DROP TABLE hobby');
        // $this->addSql('DROP TABLE job');
        // $this->addSql('DROP TABLE personne');
        // $this->addSql('DROP TABLE personne_hobby');
        // $this->addSql('DROP TABLE profile');
        // $this->addSql('DROP TABLE reset_password_request');
        // $this->addSql('DROP TABLE user');
    }
}
