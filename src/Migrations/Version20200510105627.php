<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510105627 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('create table pair_data
            (
                id serial not null
                    constraint pair_data_pk
                        primary key,
                date_time timestamp not null,
                price float not null,
                pair_id int not null
                    constraint pair_data_pair_id_fk
                        references pair
                            on update cascade on delete cascade
            );
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('drop table if exists pair_data;');
    }
}
