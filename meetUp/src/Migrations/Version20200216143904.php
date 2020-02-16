<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200216143904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE comment_event_user');
        $this->addSql('ALTER TABLE comment_event ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_event ADD CONSTRAINT FK_92349256A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92349256A76ED395 ON comment_event (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment_event_user (comment_event_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_10B910C26CB38B8D (comment_event_id), INDEX IDX_10B910C2A76ED395 (user_id), PRIMARY KEY(comment_event_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE comment_event_user ADD CONSTRAINT FK_10B910C26CB38B8D FOREIGN KEY (comment_event_id) REFERENCES comment_event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_event_user ADD CONSTRAINT FK_10B910C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment_event DROP FOREIGN KEY FK_92349256A76ED395');
        $this->addSql('DROP INDEX IDX_92349256A76ED395 ON comment_event');
        $this->addSql('ALTER TABLE comment_event DROP user_id');
    }
}
