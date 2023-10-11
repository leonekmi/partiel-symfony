<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231011082249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, author_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE help_group (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A58843C7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, member_id INT NOT NULL, help_group_id INT NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_86FFD2857597D3FE (member_id), INDEX IDX_86FFD285716F5D47 (help_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, help_group_id INT DEFAULT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A8A6C8DF675F31B (author_id), INDEX IDX_5A8A6C8D716F5D47 (help_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_liked_posts (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_616D88CE4B89032C (post_id), INDEX IDX_616D88CEA76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users_disliked_posts (post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1D9F66224B89032C (post_id), INDEX IDX_1D9F6622A76ED395 (user_id), PRIMARY KEY(post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_attachment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, file_name LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A27D07A4B89032C (post_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE help_group ADD CONSTRAINT FK_A58843C7E3C61F9 FOREIGN KEY (owner_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD2857597D3FE FOREIGN KEY (member_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE membership ADD CONSTRAINT FK_86FFD285716F5D47 FOREIGN KEY (help_group_id) REFERENCES help_group (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D716F5D47 FOREIGN KEY (help_group_id) REFERENCES help_group (id)');
        $this->addSql('ALTER TABLE users_liked_posts ADD CONSTRAINT FK_616D88CE4B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_liked_posts ADD CONSTRAINT FK_616D88CEA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_disliked_posts ADD CONSTRAINT FK_1D9F66224B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_disliked_posts ADD CONSTRAINT FK_1D9F6622A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_attachment ADD CONSTRAINT FK_5A27D07A4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE help_group DROP FOREIGN KEY FK_A58843C7E3C61F9');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD2857597D3FE');
        $this->addSql('ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285716F5D47');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D716F5D47');
        $this->addSql('ALTER TABLE users_liked_posts DROP FOREIGN KEY FK_616D88CE4B89032C');
        $this->addSql('ALTER TABLE users_liked_posts DROP FOREIGN KEY FK_616D88CEA76ED395');
        $this->addSql('ALTER TABLE users_disliked_posts DROP FOREIGN KEY FK_1D9F66224B89032C');
        $this->addSql('ALTER TABLE users_disliked_posts DROP FOREIGN KEY FK_1D9F6622A76ED395');
        $this->addSql('ALTER TABLE post_attachment DROP FOREIGN KEY FK_5A27D07A4B89032C');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE help_group');
        $this->addSql('DROP TABLE membership');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE users_liked_posts');
        $this->addSql('DROP TABLE users_disliked_posts');
        $this->addSql('DROP TABLE post_attachment');
        $this->addSql('DROP TABLE `user`');
    }
}
