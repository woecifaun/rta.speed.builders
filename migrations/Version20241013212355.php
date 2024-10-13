<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013212355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE furniture_brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE furniture_model (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_778BF88B44F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speedbuilding_category (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, name VARCHAR(255) NOT NULL, markdown LONGTEXT DEFAULT NULL, html LONGTEXT DEFAULT NULL, INDEX IDX_FB7E5CD57975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speedbuilding_record (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, url VARCHAR(255) NOT NULL, original_email_address VARCHAR(255) NOT NULL, time DOUBLE PRECISION NOT NULL, post_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', attempt INT DEFAULT NULL, INDEX IDX_222BB9E112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE furniture_model ADD CONSTRAINT FK_778BF88B44F5D008 FOREIGN KEY (brand_id) REFERENCES furniture_brand (id)');
        $this->addSql('ALTER TABLE speedbuilding_category ADD CONSTRAINT FK_FB7E5CD57975B7E7 FOREIGN KEY (model_id) REFERENCES furniture_model (id)');
        $this->addSql('ALTER TABLE speedbuilding_record ADD CONSTRAINT FK_222BB9E112469DE2 FOREIGN KEY (category_id) REFERENCES speedbuilding_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE furniture_model DROP FOREIGN KEY FK_778BF88B44F5D008');
        $this->addSql('ALTER TABLE speedbuilding_category DROP FOREIGN KEY FK_FB7E5CD57975B7E7');
        $this->addSql('ALTER TABLE speedbuilding_record DROP FOREIGN KEY FK_222BB9E112469DE2');
        $this->addSql('DROP TABLE furniture_brand');
        $this->addSql('DROP TABLE furniture_model');
        $this->addSql('DROP TABLE speedbuilding_category');
        $this->addSql('DROP TABLE speedbuilding_record');
    }
}
