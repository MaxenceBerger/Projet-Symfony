<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216141042 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_team DROP FOREIGN KEY FK_E2D604F3F8697D13');
        $this->addSql('ALTER TABLE comment_user DROP FOREIGN KEY FK_ABA574A5F8697D13');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE comment_team');
        $this->addSql('DROP TABLE comment_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment_team (comment_id INT NOT NULL, team_id INT NOT NULL, INDEX IDX_E2D604F3296CD8AE (team_id), INDEX IDX_E2D604F3F8697D13 (comment_id), PRIMARY KEY(comment_id, team_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comment_user (comment_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_ABA574A5A76ED395 (user_id), INDEX IDX_ABA574A5F8697D13 (comment_id), PRIMARY KEY(comment_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment_team ADD CONSTRAINT FK_E2D604F3296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_team ADD CONSTRAINT FK_E2D604F3F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_user ADD CONSTRAINT FK_ABA574A5F8697D13 FOREIGN KEY (comment_id) REFERENCES comment (id) ON DELETE CASCADE');
    }
}
