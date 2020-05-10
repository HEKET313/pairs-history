<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510111243 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('
            INSERT INTO pair (name) VALUES
                (\'ETHBTC\'),
                (\'LTCBTC\'),
                (\'BNBBTC\'),
                (\'NEOBTC\'),
                (\'QTUMETH\'),
                (\'EOSETH\'),
                (\'SNTETH\'),
                (\'BNTETH\'),
                (\'BCCBTC\'),
                (\'GASBTC\'),
                (\'BNBETH\'),
                (\'BTCUSDT\'),
                (\'ETHUSDT\'),
                (\'HSRBTC\'),
                (\'OAXETH\'),
                (\'DNTETH\'),
                (\'MCOETH\'),
                (\'ICNETH\'),
                (\'MCOBTC\'),
                (\'WTCBTC\'),
                (\'WTCETH\'),
                (\'LRCBTC\'),
                (\'LRCETH\'),
                (\'QTUMBTC\'),
                (\'YOYOBTC\'),
                (\'OMGBTC\'),
                (\'OMGETH\'),
                (\'ZRXBTC\'),
                (\'ZRXETH\'),
                (\'STRATBTC\'),
                (\'STRATETH\'),
                (\'SNGLSBTC\'),
                (\'SNGLSETH\'),
                (\'BQXBTC\'),
                (\'BQXETH\'),
                (\'KNCBTC\'),
                (\'KNCETH\'),
                (\'FUNBTC\'),
                (\'FUNETH\'),
                (\'SNMBTC\'),
                (\'SNMETH\'),
                (\'NEOETH\'),
                (\'IOTABTC\'),
                (\'IOTAETH\'),
                (\'LINKBTC\'),
                (\'LINKETH\'),
                (\'XVGBTC\'),
                (\'XVGETH\'),
                (\'SALTBTC\'),
                (\'SALTETH\')
        ');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM pair');
    }
}
