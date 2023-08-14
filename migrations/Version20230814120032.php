<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814120032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant ADD username VARCHAR(180) NOT NULL, ADD roles JSON NOT NULL, DROP pseudo, CHANGE id_participant id_participant INT AUTO_INCREMENT NOT NULL, CHANGE mot_passe password VARCHAR(255) NOT NULL, CHANGE mail email VARCHAR(80) NOT NULL, ADD PRIMARY KEY (id_participant)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11F85E0677 ON participant (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D79F6B11E7927C74 ON participant (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participant MODIFY id_participant INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_D79F6B11F85E0677 ON participant');
        $this->addSql('DROP INDEX UNIQ_D79F6B11E7927C74 ON participant');
        $this->addSql('DROP INDEX `primary` ON participant');
        $this->addSql('ALTER TABLE participant ADD pseudo VARCHAR(30) NOT NULL, DROP username, DROP roles, CHANGE id_participant id_participant INT NOT NULL, CHANGE email mail VARCHAR(80) NOT NULL, CHANGE password mot_passe VARCHAR(255) NOT NULL');
    }
}
