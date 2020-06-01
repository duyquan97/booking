<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200528034224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, province VARCHAR(255) DEFAULT NULL, district VARCHAR(255) DEFAULT NULL, street VARCHAR(255) DEFAULT NULL, type INT DEFAULT NULL, status INT DEFAULT NULL, featured INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE date_room (id INT AUTO_INCREMENT NOT NULL, from_date DATE DEFAULT NULL, to_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE set_room (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, price_room_id INT DEFAULT NULL, date_room_id INT DEFAULT NULL, room_count INT DEFAULT NULL, person INT DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E758B31254177093 (room_id), INDEX IDX_E758B3123F31B8CF (price_room_id), INDEX IDX_E758B3126DF0776E (date_room_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE price_room (id INT AUTO_INCREMENT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE set_room ADD CONSTRAINT FK_E758B31254177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE set_room ADD CONSTRAINT FK_E758B3123F31B8CF FOREIGN KEY (price_room_id) REFERENCES price_room (id)');
        $this->addSql('ALTER TABLE set_room ADD CONSTRAINT FK_E758B3126DF0776E FOREIGN KEY (date_room_id) REFERENCES date_room (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE set_room DROP FOREIGN KEY FK_E758B31254177093');
        $this->addSql('ALTER TABLE set_room DROP FOREIGN KEY FK_E758B3126DF0776E');
        $this->addSql('ALTER TABLE set_room DROP FOREIGN KEY FK_E758B3123F31B8CF');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE date_room');
        $this->addSql('DROP TABLE set_room');
        $this->addSql('DROP TABLE price_room');
    }
}
