<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200626135535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE poblacion (id INT AUTO_INCREMENT NOT NULL, provincia_id INT DEFAULT NULL, municipio_id INT DEFAULT NULL, codigo VARCHAR(10) NOT NULL, nombre VARCHAR(255) NOT NULL, nombre_alternativo VARCHAR(255) DEFAULT NULL, nombre_alternativo_bis VARCHAR(255) DEFAULT NULL, descripcion VARCHAR(50) DEFAULT NULL, gid VARCHAR(50) DEFAULT NULL, INDEX IDX_7C27B8AA58BC1BE0 (municipio_id), INDEX nombre (nombre), INDEX nombrealtbis (nombre_alternativo_bis), INDEX IDX_7C27B8AA4E7121AF (provincia_id), INDEX gid (gid), INDEX nombrealt (nombre_alternativo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donacion_log (id INT AUTO_INCREMENT NOT NULL, id_donacion INT NOT NULL, id_servicio INT NOT NULL, user_id INT NOT NULL, estado_donacion INT DEFAULT NULL, estado_servicio INT DEFAULT NULL, fecha DATETIME DEFAULT NULL, INDEX IDX_6B61262CACF00213 (id_donacion), INDEX IDX_6B61262C9B5D1EBF (id_servicio), INDEX IDX_6B61262CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE integrantes (id INT AUTO_INCREMENT NOT NULL, relacion INT DEFAULT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) NOT NULL, telefono VARCHAR(14) DEFAULT NULL, discapacidad INT NOT NULL, fnacim DATE DEFAULT NULL, sitLaboral INT DEFAULT NULL, beneficiarioId INT NOT NULL, UNIQUE INDEX UNIQ_4F5A6E10890D3559 (sitLaboral), UNIQUE INDEX UNIQ_4F5A6E10AF47286F (relacion), INDEX IDX_4F5A6E10D3C390D (beneficiarioId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donacion_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, donacion_id INT NOT NULL, INDEX IDX_54A1D2DBA76ED395 (user_id), INDEX IDX_54A1D2DBEFB885DC (donacion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pais (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(255) NOT NULL, nombre VARCHAR(255) NOT NULL, descr VARCHAR(255) DEFAULT NULL, prefijo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voluntario (id INT AUTO_INCREMENT NOT NULL, userid INT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) NOT NULL, telefono VARCHAR(20) DEFAULT NULL, ambitoRecogida JSON NOT NULL, ambitoEntrega JSON NOT NULL, lopd TINYINT(1) NOT NULL, fecha DATETIME NOT NULL, fechaModi DATETIME DEFAULT NULL, INDEX IDX_216231FEF132696E (userid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codigopostal (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(5) NOT NULL, lat NUMERIC(31, 14) DEFAULT NULL, lon NUMERIC(31, 14) DEFAULT NULL, distance NUMERIC(31, 14) DEFAULT NULL, long_name VARCHAR(50) DEFAULT NULL, short_name VARCHAR(50) DEFAULT NULL, formatted_address VARCHAR(100) DEFAULT NULL, northeastLon NUMERIC(31, 14) DEFAULT NULL, northeastLat NUMERIC(31, 14) DEFAULT NULL, southwestLon NUMERIC(31, 14) DEFAULT NULL, southwestLat NUMERIC(31, 14) DEFAULT NULL, UNIQUE INDEX UNIQ_6AF9490420332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situacion_laboral (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proc_ingresos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proveedor (id INT AUTO_INCREMENT NOT NULL, userid INT NOT NULL, provincia INT NOT NULL, municipio INT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) NOT NULL, direccion VARCHAR(255) DEFAULT NULL, telefono VARCHAR(20) DEFAULT NULL, codPostal VARCHAR(6) NOT NULL, redesSociales TINYINT(1) DEFAULT NULL, lopd TINYINT(1) NOT NULL, fecha DATETIME NOT NULL, fechaModi DATETIME DEFAULT NULL, INDEX IDX_16C068CEF132696E (userid), INDEX IDX_16C068CED39AF213 (provincia), INDEX IDX_16C068CEFE98F5E0 (municipio), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donacion (id INT AUTO_INCREMENT NOT NULL, producto_id INT NOT NULL, proveedor_id INT NOT NULL, cantidad INT NOT NULL, total INT NOT NULL, fecha DATETIME NOT NULL, fechaModi DATETIME DEFAULT NULL, estado INT NOT NULL, INDEX IDX_FC2BEE867645698E (producto_id), INDEX IDX_FC2BEE86CB305D73 (proveedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE municipio (id INT AUTO_INCREMENT NOT NULL, provincia_id INT NOT NULL, codigo VARCHAR(4) NOT NULL, nombre VARCHAR(255) NOT NULL, nombre_alternativo VARCHAR(255) NOT NULL, nombre_alternativo_bis VARCHAR(255) DEFAULT NULL, gid VARCHAR(50) DEFAULT NULL, INDEX IDX_FE98F5E04E7121AF (provincia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, surname VARCHAR(150) NOT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, telephone VARCHAR(20) DEFAULT NULL, datecreate DATETIME DEFAULT NULL, username VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, active INT NOT NULL, apiToken VARCHAR(255) DEFAULT NULL, tokenPushBots VARCHAR(255) DEFAULT NULL, idPushBots VARCHAR(255) DEFAULT NULL, oneSignalPlayerId VARCHAR(255) DEFAULT NULL, plataforma VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E22488D7 (apiToken), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, padreid INT DEFAULT NULL, description TEXT DEFAULT NULL, file TEXT DEFAULT NULL, line TEXT DEFAULT NULL, `function` VARCHAR(50) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, pais_id INT DEFAULT NULL, codigo VARCHAR(10) NOT NULL, nombre VARCHAR(255) NOT NULL, nombre_alternativo VARCHAR(255) DEFAULT NULL, nombre_alternativo_bis VARCHAR(255) DEFAULT NULL, comunidad_id INT DEFAULT NULL, prefijo VARCHAR(15) NOT NULL, zona VARCHAR(255) NOT NULL, descripcion VARCHAR(450) DEFAULT NULL, temperatura DOUBLE PRECISION DEFAULT NULL, gid VARCHAR(50) DEFAULT NULL, INDEX IDX_D39AF213C604D5C6 (pais_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beneficiario (id INT AUTO_INCREMENT NOT NULL, userid INT NOT NULL, provincia INT NOT NULL, municipio INT NOT NULL, ingresos INT NOT NULL, nombre VARCHAR(50) NOT NULL, apellidos VARCHAR(100) DEFAULT NULL, telefono VARCHAR(20) DEFAULT NULL, discapacidad INT DEFAULT NULL, fnacim DATETIME DEFAULT NULL, codPostal VARCHAR(6) DEFAULT NULL, lopd TINYINT(1) DEFAULT NULL, fecha DATETIME NOT NULL, direccion LONGTEXT DEFAULT NULL, fechaModi DATETIME DEFAULT NULL, sitLaboral INT NOT NULL, procIngresos INT NOT NULL, INDEX IDX_E8D0B617D39AF213 (provincia), INDEX IDX_E8D0B617FE98F5E0 (municipio), INDEX IDX_E8D0B617890D3559 (sitLaboral), INDEX IDX_E8D0B61796CA59DA (ingresos), INDEX IDX_E8D0B617B64DAAD0 (procIngresos), INDEX FK_beneficiario_user (userid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE horarios (id INT AUTO_INCREMENT NOT NULL, proveedorid INT DEFAULT NULL, dia INT NOT NULL, abre VARCHAR(255) DEFAULT NULL, cierra VARCHAR(255) DEFAULT NULL, abierto INT NOT NULL, INDEX IDX_5433650AA929623A (proveedorid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE servicio (id INT AUTO_INCREMENT NOT NULL, donacion_id INT NOT NULL, beneficiario_id INT DEFAULT NULL, voluntario_id INT DEFAULT NULL, cantidad INT NOT NULL, fecha DATETIME NOT NULL, fechaModi DATETIME DEFAULT NULL, estado SMALLINT NOT NULL, rutaFoto VARCHAR(200) DEFAULT NULL, INDEX IDX_CB86F22AEFB885DC (donacion_id), INDEX IDX_CB86F22A4B64ABC7 (beneficiario_id), INDEX IDX_CB86F22ABCFA9C0D (voluntario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingresos (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE poblacion ADD CONSTRAINT FK_FED63A094E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE donacion_log ADD CONSTRAINT FK_6B61262CACF00213 FOREIGN KEY (id_donacion) REFERENCES donacion (id)');
        $this->addSql('ALTER TABLE donacion_log ADD CONSTRAINT FK_6B61262C9B5D1EBF FOREIGN KEY (id_servicio) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE donacion_log ADD CONSTRAINT FK_6B61262CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE integrantes ADD CONSTRAINT FK_4F5A6E10890D3559 FOREIGN KEY (sitLaboral) REFERENCES situacion_laboral (id)');
        $this->addSql('ALTER TABLE integrantes ADD CONSTRAINT FK_4F5A6E10AF47286F FOREIGN KEY (relacion) REFERENCES relacion (id)');
        $this->addSql('ALTER TABLE integrantes ADD CONSTRAINT FK_4F5A6E10D3C390D FOREIGN KEY (beneficiarioId) REFERENCES beneficiario (id)');
        $this->addSql('ALTER TABLE donacion_user ADD CONSTRAINT FK_54A1D2DBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donacion_user ADD CONSTRAINT FK_54A1D2DBEFB885DC FOREIGN KEY (donacion_id) REFERENCES donacion (id)');
        $this->addSql('ALTER TABLE voluntario ADD CONSTRAINT FK_216231FEF132696E FOREIGN KEY (userid) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proveedor ADD CONSTRAINT FK_16C068CEF132696E FOREIGN KEY (userid) REFERENCES user (id)');
        $this->addSql('ALTER TABLE proveedor ADD CONSTRAINT FK_16C068CED39AF213 FOREIGN KEY (provincia) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE proveedor ADD CONSTRAINT FK_16C068CEFE98F5E0 FOREIGN KEY (municipio) REFERENCES municipio (id)');
        $this->addSql('ALTER TABLE donacion ADD CONSTRAINT FK_FC2BEE867645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE donacion ADD CONSTRAINT FK_FC2BEE86CB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id)');
        $this->addSql('ALTER TABLE municipio ADD CONSTRAINT FK_FE98F5E04E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE provincia ADD CONSTRAINT FK_D39AF213C604D5C6 FOREIGN KEY (pais_id) REFERENCES pais (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B617F132696E FOREIGN KEY (userid) REFERENCES user (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B617D39AF213 FOREIGN KEY (provincia) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B617FE98F5E0 FOREIGN KEY (municipio) REFERENCES municipio (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B617890D3559 FOREIGN KEY (sitLaboral) REFERENCES situacion_laboral (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B61796CA59DA FOREIGN KEY (ingresos) REFERENCES ingresos (id)');
        $this->addSql('ALTER TABLE beneficiario ADD CONSTRAINT FK_E8D0B617B64DAAD0 FOREIGN KEY (procIngresos) REFERENCES proc_ingresos (id)');
        $this->addSql('ALTER TABLE horarios ADD CONSTRAINT FK_5433650AA929623A FOREIGN KEY (proveedorid) REFERENCES proveedor (id)');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_CB86F22AEFB885DC FOREIGN KEY (donacion_id) REFERENCES donacion (id)');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_CB86F22A4B64ABC7 FOREIGN KEY (beneficiario_id) REFERENCES beneficiario (id)');
        $this->addSql('ALTER TABLE servicio ADD CONSTRAINT FK_CB86F22ABCFA9C0D FOREIGN KEY (voluntario_id) REFERENCES voluntario (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE donacion DROP FOREIGN KEY FK_FC2BEE867645698E');
        $this->addSql('ALTER TABLE provincia DROP FOREIGN KEY FK_D39AF213C604D5C6');
        $this->addSql('ALTER TABLE servicio DROP FOREIGN KEY FK_CB86F22ABCFA9C0D');
        $this->addSql('ALTER TABLE integrantes DROP FOREIGN KEY FK_4F5A6E10890D3559');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B617890D3559');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B617B64DAAD0');
        $this->addSql('ALTER TABLE donacion DROP FOREIGN KEY FK_FC2BEE86CB305D73');
        $this->addSql('ALTER TABLE horarios DROP FOREIGN KEY FK_5433650AA929623A');
        $this->addSql('ALTER TABLE donacion_log DROP FOREIGN KEY FK_6B61262CACF00213');
        $this->addSql('ALTER TABLE donacion_user DROP FOREIGN KEY FK_54A1D2DBEFB885DC');
        $this->addSql('ALTER TABLE servicio DROP FOREIGN KEY FK_CB86F22AEFB885DC');
        $this->addSql('ALTER TABLE proveedor DROP FOREIGN KEY FK_16C068CEFE98F5E0');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B617FE98F5E0');
        $this->addSql('ALTER TABLE donacion_log DROP FOREIGN KEY FK_6B61262CA76ED395');
        $this->addSql('ALTER TABLE donacion_user DROP FOREIGN KEY FK_54A1D2DBA76ED395');
        $this->addSql('ALTER TABLE voluntario DROP FOREIGN KEY FK_216231FEF132696E');
        $this->addSql('ALTER TABLE proveedor DROP FOREIGN KEY FK_16C068CEF132696E');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B617F132696E');
        $this->addSql('ALTER TABLE poblacion DROP FOREIGN KEY FK_FED63A094E7121AF');
        $this->addSql('ALTER TABLE proveedor DROP FOREIGN KEY FK_16C068CED39AF213');
        $this->addSql('ALTER TABLE municipio DROP FOREIGN KEY FK_FE98F5E04E7121AF');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B617D39AF213');
        $this->addSql('ALTER TABLE integrantes DROP FOREIGN KEY FK_4F5A6E10D3C390D');
        $this->addSql('ALTER TABLE servicio DROP FOREIGN KEY FK_CB86F22A4B64ABC7');
        $this->addSql('ALTER TABLE integrantes DROP FOREIGN KEY FK_4F5A6E10AF47286F');
        $this->addSql('ALTER TABLE donacion_log DROP FOREIGN KEY FK_6B61262C9B5D1EBF');
        $this->addSql('ALTER TABLE beneficiario DROP FOREIGN KEY FK_E8D0B61796CA59DA');
        $this->addSql('DROP TABLE poblacion');
        $this->addSql('DROP TABLE donacion_log');
        $this->addSql('DROP TABLE producto');
        $this->addSql('DROP TABLE integrantes');
        $this->addSql('DROP TABLE donacion_user');
        $this->addSql('DROP TABLE pais');
        $this->addSql('DROP TABLE voluntario');
        $this->addSql('DROP TABLE codigopostal');
        $this->addSql('DROP TABLE situacion_laboral');
        $this->addSql('DROP TABLE proc_ingresos');
        $this->addSql('DROP TABLE proveedor');
        $this->addSql('DROP TABLE donacion');
        $this->addSql('DROP TABLE municipio');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('DROP TABLE beneficiario');
        $this->addSql('DROP TABLE horarios');
        $this->addSql('DROP TABLE relacion');
        $this->addSql('DROP TABLE servicio');
        $this->addSql('DROP TABLE ingresos');
    }
}
