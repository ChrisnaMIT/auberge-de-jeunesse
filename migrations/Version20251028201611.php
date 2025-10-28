<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251028201611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('CREATE TABLE amenity (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE bed (id SERIAL NOT NULL, room_id INT NOT NULL, number VARCHAR(255) NOT NULL, status VARCHAR(50) DEFAULT \'available\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E647FCFF54177093 ON bed (room_id)');
        $this->addSql('CREATE TABLE room (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_mixed BOOLEAN DEFAULT false NOT NULL, number_of_beds INT DEFAULT NULL, price_per_night DOUBLE PRECISION DEFAULT NULL, status VARCHAR(50) DEFAULT \'available\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE room_amenity (room_id INT NOT NULL, amenity_id INT NOT NULL, PRIMARY KEY(room_id, amenity_id))');
        $this->addSql('CREATE INDEX IDX_4742C58354177093 ON room_amenity (room_id)');
        $this->addSql('CREATE INDEX IDX_4742C5839F9F1305 ON room_amenity (amenity_id)');
        $this->addSql('ALTER TABLE bed ADD CONSTRAINT FK_E647FCFF54177093 FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_amenity ADD CONSTRAINT FK_4742C58354177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE room_amenity ADD CONSTRAINT FK_4742C5839F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE "user"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname TEXT NOT NULL, lastname TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_identifier_email ON "user" (email)');
        $this->addSql('ALTER TABLE bed DROP CONSTRAINT FK_E647FCFF54177093');
        $this->addSql('ALTER TABLE room_amenity DROP CONSTRAINT FK_4742C58354177093');
        $this->addSql('ALTER TABLE room_amenity DROP CONSTRAINT FK_4742C5839F9F1305');
        $this->addSql('DROP TABLE amenity');
        $this->addSql('DROP TABLE bed');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE room_amenity');
    }
}
