<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230816121140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D0968116C6E55B5 ON campus (nom)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_55CAF762A4D60759 ON etat (libelle)');
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION DEFAULT NULL, CHANGE longitude longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE participant RENAME INDEX uniq_d79f6b11f85e0677 TO UNIQ_D79F6B1186CC499D');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE available_at available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9D0968116C6E55B5 ON campus');
        $this->addSql('DROP INDEX UNIQ_55CAF762A4D60759 ON etat');
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE created_at created_at DATETIME NOT NULL, CHANGE available_at available_at DATETIME NOT NULL, CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE participant RENAME INDEX uniq_d79f6b1186cc499d TO UNIQ_D79F6B11F85E0677');
    }
}
