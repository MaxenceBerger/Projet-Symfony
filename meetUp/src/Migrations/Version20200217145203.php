<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200217145203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_team DROP FOREIGN KEY FK_E2D604F371F7E88B');
        $this->addSql('DROP INDEX IDX_E2D604F371F7E88B ON comment_team');
        $this->addSql('ALTER TABLE comment_team CHANGE event_id team_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_team ADD CONSTRAINT FK_E2D604F3296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_E2D604F3296CD8AE ON comment_team (team_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment_team DROP FOREIGN KEY FK_E2D604F3296CD8AE');
        $this->addSql('DROP INDEX IDX_E2D604F3296CD8AE ON comment_team');
        $this->addSql('ALTER TABLE comment_team CHANGE team_id event_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_team ADD CONSTRAINT FK_E2D604F371F7E88B FOREIGN KEY (event_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_E2D604F371F7E88B ON comment_team (event_id)');
    }
}
