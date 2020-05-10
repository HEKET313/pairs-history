<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510104408 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('create table pair (
            id serial not null
                constraint pair_pk
                    primary key,
            name varchar(20) not null
        )');
        $this->addSql('create unique index pair_name_uindex on pair (name)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS pair');
    }
}
