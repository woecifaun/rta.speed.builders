<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102225659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speedbuilding_record ADD speedbuilder_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE speedbuilding_record ADD CONSTRAINT FK_222BB9E11FA8990A FOREIGN KEY (speedbuilder_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_222BB9E11FA8990A ON speedbuilding_record (speedbuilder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE speedbuilding_record DROP FOREIGN KEY FK_222BB9E11FA8990A');
        $this->addSql('DROP INDEX IDX_222BB9E11FA8990A ON speedbuilding_record');
        $this->addSql('ALTER TABLE speedbuilding_record DROP speedbuilder_id');
    }
}
