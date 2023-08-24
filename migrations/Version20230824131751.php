<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824131751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gift ADD gift_list_id INT DEFAULT NULL, ADD reserved_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D51F42524 FOREIGN KEY (gift_list_id) REFERENCES gift_list (id)');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DBCDB4AF4 FOREIGN KEY (reserved_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_A47C990D51F42524 ON gift (gift_list_id)');
        $this->addSql('CREATE INDEX IDX_A47C990DBCDB4AF4 ON gift (reserved_by_id)');
        $this->addSql('ALTER TABLE gift_list ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gift_list ADD CONSTRAINT FK_B6B50A45A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6B50A45A76ED395 ON gift_list (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990D51F42524');
        $this->addSql('ALTER TABLE gift DROP FOREIGN KEY FK_A47C990DBCDB4AF4');
        $this->addSql('DROP INDEX IDX_A47C990D51F42524 ON gift');
        $this->addSql('DROP INDEX IDX_A47C990DBCDB4AF4 ON gift');
        $this->addSql('ALTER TABLE gift DROP gift_list_id, DROP reserved_by_id');
        $this->addSql('ALTER TABLE gift_list DROP FOREIGN KEY FK_B6B50A45A76ED395');
        $this->addSql('DROP INDEX IDX_B6B50A45A76ED395 ON gift_list');
        $this->addSql('ALTER TABLE gift_list DROP user_id');
    }
}
