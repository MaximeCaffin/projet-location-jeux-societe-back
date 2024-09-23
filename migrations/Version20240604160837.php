<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240604160837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, company VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_favorite (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3A726436E48FD905 (game_id), INDEX IDX_3A726436A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_rent (id INT AUTO_INCREMENT NOT NULL, rent_id INT NOT NULL, game_id INT NOT NULL, INDEX IDX_CF1AA009E5FD6250 (rent_id), INDEX IDX_CF1AA009E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, price VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, quantity INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_232B318C12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rent (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, status VARCHAR(255) DEFAULT NULL, INDEX IDX_2784DCCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_favorite ADD CONSTRAINT FK_3A726436E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE content_favorite ADD CONSTRAINT FK_3A726436A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE content_rent ADD CONSTRAINT FK_CF1AA009E5FD6250 FOREIGN KEY (rent_id) REFERENCES rent (id)');
        $this->addSql('ALTER TABLE content_rent ADD CONSTRAINT FK_CF1AA009E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_2784DCCA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE content_favorite DROP FOREIGN KEY FK_3A726436E48FD905');
        $this->addSql('ALTER TABLE content_favorite DROP FOREIGN KEY FK_3A726436A76ED395');
        $this->addSql('ALTER TABLE content_rent DROP FOREIGN KEY FK_CF1AA009E5FD6250');
        $this->addSql('ALTER TABLE content_rent DROP FOREIGN KEY FK_CF1AA009E48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C12469DE2');
        $this->addSql('ALTER TABLE rent DROP FOREIGN KEY FK_2784DCCA76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE content_favorite');
        $this->addSql('DROP TABLE content_rent');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE rent');
        $this->addSql('DROP TABLE `user`');
    }
}
