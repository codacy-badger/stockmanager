<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190130145503 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mass_processing (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, treated INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT NULL, CHANGE date_ready date_ready DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT NULL, CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT NULL, CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT NULL, CHANGE soft_upload soft_upload TINYINT(1) DEFAULT NULL, CHANGE soft_version soft_version VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment_status ADD issue_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE start_failure start_failure DATETIME DEFAULT NULL, CHANGE end_failure end_failure DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment_status ADD CONSTRAINT FK_DC8E1A5C5E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DC8E1A5C5E7AA58C ON equipment_status (issue_id)');
        $this->addSql('ALTER TABLE location CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE is_ok is_ok TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mass_processing');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment_status DROP FOREIGN KEY FK_DC8E1A5C5E7AA58C');
        $this->addSql('DROP INDEX UNIQ_DC8E1A5C5E7AA58C ON equipment_status');
        $this->addSql('ALTER TABLE equipment_status DROP issue_id, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE start_failure start_failure DATETIME DEFAULT \'NULL\', CHANGE end_failure end_failure DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT \'NULL\', CHANGE date_ready date_ready DATETIME DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE location CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE is_ok is_ok TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT \'NULL\', CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT \'NULL\', CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT \'NULL\', CHANGE soft_upload soft_upload TINYINT(1) DEFAULT \'NULL\', CHANGE soft_version soft_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
    }
}
