<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125130650 extends AbstractMigration
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
        $this->addSql('CREATE TABLE speedbuilding_category (id INT AUTO_INCREMENT NOT NULL, model_id INT NOT NULL, created_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, markdown LONGTEXT DEFAULT NULL, html LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_FB7E5CD57975B7E7 (model_id), INDEX IDX_FB7E5CD5B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speedbuilding_category_version (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, author_id INT NOT NULL, name VARCHAR(255) NOT NULL, markdown LONGTEXT NOT NULL, version INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6F10C4F812469DE2 (category_id), INDEX IDX_6F10C4F8F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speedbuilding_record (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, speedbuilder_id INT DEFAULT NULL, video_url VARCHAR(255) NOT NULL, original_email_address VARCHAR(255) DEFAULT NULL, time DOUBLE PRECISION NOT NULL, post_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', attempt INT DEFAULT NULL, video_platform VARCHAR(255) NOT NULL, video_id VARCHAR(255) NOT NULL, rank INT DEFAULT NULL, best_rank INT DEFAULT NULL, INDEX IDX_222BB9E112469DE2 (category_id), INDEX IDX_222BB9E11FA8990A (speedbuilder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', username VARCHAR(255) NOT NULL, email_address VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, registered_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', display_name VARCHAR(255) DEFAULT NULL, status VARCHAR(50) NOT NULL, country VARCHAR(3) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649B08E074E (email_address), UNIQUE INDEX UNIQ_8D93D649D5499347 (display_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_validation_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL COMMENT \'(DC2Type:datetimetz_immutable)\', validated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) DEFAULT NULL, INDEX IDX_CA31E6F5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE furniture_model ADD CONSTRAINT FK_778BF88B44F5D008 FOREIGN KEY (brand_id) REFERENCES furniture_brand (id)');
        $this->addSql('ALTER TABLE speedbuilding_category ADD CONSTRAINT FK_FB7E5CD57975B7E7 FOREIGN KEY (model_id) REFERENCES furniture_model (id)');
        $this->addSql('ALTER TABLE speedbuilding_category ADD CONSTRAINT FK_FB7E5CD5B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE speedbuilding_category_version ADD CONSTRAINT FK_6F10C4F812469DE2 FOREIGN KEY (category_id) REFERENCES speedbuilding_category (id)');
        $this->addSql('ALTER TABLE speedbuilding_category_version ADD CONSTRAINT FK_6F10C4F8F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE speedbuilding_record ADD CONSTRAINT FK_222BB9E112469DE2 FOREIGN KEY (category_id) REFERENCES speedbuilding_category (id)');
        $this->addSql('ALTER TABLE speedbuilding_record ADD CONSTRAINT FK_222BB9E11FA8990A FOREIGN KEY (speedbuilder_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_validation_token ADD CONSTRAINT FK_CA31E6F5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE furniture_model DROP FOREIGN KEY FK_778BF88B44F5D008');
        $this->addSql('ALTER TABLE speedbuilding_category DROP FOREIGN KEY FK_FB7E5CD57975B7E7');
        $this->addSql('ALTER TABLE speedbuilding_category DROP FOREIGN KEY FK_FB7E5CD5B03A8386');
        $this->addSql('ALTER TABLE speedbuilding_category_version DROP FOREIGN KEY FK_6F10C4F812469DE2');
        $this->addSql('ALTER TABLE speedbuilding_category_version DROP FOREIGN KEY FK_6F10C4F8F675F31B');
        $this->addSql('ALTER TABLE speedbuilding_record DROP FOREIGN KEY FK_222BB9E112469DE2');
        $this->addSql('ALTER TABLE speedbuilding_record DROP FOREIGN KEY FK_222BB9E11FA8990A');
        $this->addSql('ALTER TABLE user_validation_token DROP FOREIGN KEY FK_CA31E6F5A76ED395');
        $this->addSql('DROP TABLE furniture_brand');
        $this->addSql('DROP TABLE furniture_model');
        $this->addSql('DROP TABLE speedbuilding_category');
        $this->addSql('DROP TABLE speedbuilding_category_version');
        $this->addSql('DROP TABLE speedbuilding_record');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_validation_token');
    }
}
