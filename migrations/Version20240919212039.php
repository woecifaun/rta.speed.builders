<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919212039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assembly (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, url VARCHAR(255) NOT NULL, original_email_address VARCHAR(255) NOT NULL, time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', post_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', attempt INT DEFAULT NULL, INDEX IDX_1A09EF0B12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, brand VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, markdown LONGTEXT DEFAULT NULL, html LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assembly ADD CONSTRAINT FK_1A09EF0B12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assembly DROP FOREIGN KEY FK_1A09EF0B12469DE2');
        $this->addSql('DROP TABLE assembly');
        $this->addSql('DROP TABLE category');
    }
}
