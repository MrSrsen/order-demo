<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250207133549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order, order_item and order_sequence tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, number VARCHAR(32) NOT NULL, created_at DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', title VARCHAR(255) NOT NULL, total NUMERIC(13, 2) NOT NULL, currency VARCHAR(3) NOT NULL, state VARCHAR(16) NOT NULL, UNIQUE INDEX UNIQ_F529939896901F54 (number), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, order_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, unit_price NUMERIC(13, 2) NOT NULL, quantity NUMERIC(13, 2) NOT NULL, total NUMERIC(13, 2) NOT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_sequence (id INT AUTO_INCREMENT NOT NULL, `index` INT DEFAULT NULL, version INT DEFAULT 1, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE order_sequence');
    }
}
