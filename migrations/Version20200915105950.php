<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915105950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE complexity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE employee_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE material_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "order_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE service_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE urgency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, client_user INT NOT NULL, phone VARCHAR(11) NOT NULL, registration_date DATE NOT NULL, is_permanent BOOLEAN NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) DEFAULT NULL, patronymic VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C74404555C0F152B ON client (client_user)');
        $this->addSql('CREATE TABLE complexity (id INT NOT NULL, name VARCHAR(20) NOT NULL, pricing_coefficient DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE employee (id INT NOT NULL, employee_user INT DEFAULT NULL, passport VARCHAR(10) NOT NULL, salary NUMERIC(10, 0) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, patronymic VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5D9F75A1384A9C0E ON employee (employee_user)');
        $this->addSql('CREATE TABLE material (id INT NOT NULL, name VARCHAR(50) NOT NULL, price NUMERIC(10, 0) NOT NULL, available DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "order" (id INT NOT NULL, order_complexity INT DEFAULT NULL, order_urgency INT DEFAULT NULL, order_service INT DEFAULT NULL, order_employee INT DEFAULT NULL, order_client INT DEFAULT NULL, receive_date DATE NOT NULL, ending_date DATE NOT NULL, active BOOLEAN NOT NULL, completed BOOLEAN NOT NULL, client_mark INT DEFAULT NULL, sum_price NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398EB3F3BCE ON "order" (order_complexity)');
        $this->addSql('CREATE INDEX IDX_F529939891069EC9 ON "order" (order_urgency)');
        $this->addSql('CREATE INDEX IDX_F529939817E73399 ON "order" (order_service)');
        $this->addSql('CREATE INDEX IDX_F5299398BC679710 ON "order" (order_employee)');
        $this->addSql('CREATE INDEX IDX_F52993984CB1480 ON "order" (order_client)');
        $this->addSql('CREATE TABLE service (id INT NOT NULL, name VARCHAR(50) NOT NULL, standard_pricing NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE urgency (id INT NOT NULL, name VARCHAR(20) NOT NULL, pricing_coefficient DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE uses_material (service_id INT NOT NULL, materials_id INT NOT NULL, uses_quantity DOUBLE PRECISION NOT NULL, PRIMARY KEY(service_id, materials_id))');
        $this->addSql('CREATE INDEX IDX_73F1FAABED5CA9E6 ON uses_material (service_id)');
        $this->addSql('CREATE INDEX IDX_73F1FAAB3A9FC940 ON uses_material (materials_id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404555C0F152B FOREIGN KEY (client_user) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1384A9C0E FOREIGN KEY (employee_user) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398EB3F3BCE FOREIGN KEY (order_complexity) REFERENCES complexity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939891069EC9 FOREIGN KEY (order_urgency) REFERENCES urgency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F529939817E73399 FOREIGN KEY (order_service) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398BC679710 FOREIGN KEY (order_employee) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993984CB1480 FOREIGN KEY (order_client) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uses_material ADD CONSTRAINT FK_73F1FAABED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE uses_material ADD CONSTRAINT FK_73F1FAAB3A9FC940 FOREIGN KEY (materials_id) REFERENCES material (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993984CB1480');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398EB3F3BCE');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398BC679710');
        $this->addSql('ALTER TABLE uses_material DROP CONSTRAINT FK_73F1FAAB3A9FC940');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939817E73399');
        $this->addSql('ALTER TABLE uses_material DROP CONSTRAINT FK_73F1FAABED5CA9E6');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F529939891069EC9');
        $this->addSql('ALTER TABLE client DROP CONSTRAINT FK_C74404555C0F152B');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1384A9C0E');
        $this->addSql('DROP SEQUENCE client_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE complexity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE employee_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE material_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "order_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE service_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE urgency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE complexity');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE material');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE urgency');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE uses_material');
    }
}
