<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201017134237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE developers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, estimated_duration INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql("insert into developers (name,level,estimated_duration) values ('DEV1',1,1);");
        $this->addSql("insert into developers (name,level,estimated_duration) values ('DEV2',2,1);");
        $this->addSql("insert into developers (name,level,estimated_duration) values ('DEV3',3,1);");
        $this->addSql("insert into developers (name,level,estimated_duration) values ('DEV4',4,1);");
        $this->addSql("insert into developers (name,level,estimated_duration) values ('DEV5',5,1);");

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE developers');
    }
}
