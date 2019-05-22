<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190521123456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL, CHANGE is_embeded is_embeded TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE delivery CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT NULL, CHANGE date_ready date_ready DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE location CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE part1_id part1_id INT DEFAULT NULL, CHANGE part2_id part2_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT NULL, CHANGE is_ok is_ok TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE mass_processing CHANGE treated treated INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE part_group_id part_group_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE threshold threshold INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_group CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_quantity CHANGE part_id part_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair ADD document_file_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE issue_id issue_id INT DEFAULT NULL, CHANGE software_id software_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT NULL, CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT NULL, CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT NULL, CHANGE soft_upload soft_upload TINYINT(1) DEFAULT NULL, CHANGE soft_version soft_version VARCHAR(255) DEFAULT NULL, CHANGE unavailability unavailability INT DEFAULT NULL, CHANGE is_going_to_subcontractor is_going_to_subcontractor TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE repair ADD CONSTRAINT FK_8EE4342137A4DFB0 FOREIGN KEY (document_file_id) REFERENCES document (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8EE4342137A4DFB0 ON repair (document_file_id)');
        $this->addSql('ALTER TABLE report CHANGE category_id category_id INT DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE mtbf mtbf NUMERIC(10, 2) DEFAULT NULL, CHANGE mttr mttr NUMERIC(10, 2) DEFAULT NULL, CHANGE rate rate NUMERIC(5, 2) DEFAULT NULL, CHANGE current_issue_quantity current_issue_quantity INT DEFAULT NULL, CHANGE subcontractor_issue_quantity subcontractor_issue_quantity INT DEFAULT NULL, CHANGE degradation_quantity degradation_quantity INT DEFAULT NULL, CHANGE new_issue_quantity new_issue_quantity INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report_contract CHANGE category_id category_id INT DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE mtbf mtbf NUMERIC(10, 2) DEFAULT NULL, CHANGE mttr mttr NUMERIC(10, 2) DEFAULT NULL, CHANGE rate rate NUMERIC(5, 2) DEFAULT NULL, CHANGE current_issue_quantity current_issue_quantity INT DEFAULT NULL, CHANGE subcontractor_issue_quantity subcontractor_issue_quantity INT DEFAULT NULL, CHANGE degradation_quantity degradation_quantity INT DEFAULT NULL, CHANGE new_issue_quantity new_issue_quantity INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE search CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE software CHANGE brand_id brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subcontractor_repair CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE date_entry date_entry DATE DEFAULT NULL, CHANGE date_dispatch date_dispatch DATE DEFAULT NULL, CHANGE date_return date_return DATE DEFAULT NULL, CHANGE rma rma VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE token CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL, CHANGE token_id token_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE repair DROP FOREIGN KEY FK_8EE4342137A4DFB0');
        $this->addSql('DROP TABLE document');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL, CHANGE is_embeded is_embeded TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE delivery CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT \'NULL\', CHANGE date_ready date_ready DATETIME DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE location CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE part1_id part1_id INT DEFAULT NULL, CHANGE part2_id part2_id INT DEFAULT NULL, CHANGE date date DATETIME DEFAULT \'NULL\', CHANGE is_ok is_ok TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE mass_processing CHANGE treated treated INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE part_group_id part_group_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE threshold threshold INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_group CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_quantity CHANGE part_id part_id INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_8EE4342137A4DFB0 ON repair');
        $this->addSql('ALTER TABLE repair DROP document_file_id, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE software_id software_id INT DEFAULT NULL, CHANGE issue_id issue_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT \'NULL\', CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT \'NULL\', CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT \'NULL\', CHANGE soft_upload soft_upload TINYINT(1) DEFAULT \'NULL\', CHANGE soft_version soft_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE unavailability unavailability INT DEFAULT NULL, CHANGE is_going_to_subcontractor is_going_to_subcontractor TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE report CHANGE category_id category_id INT DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE mtbf mtbf NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE mttr mttr NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE rate rate NUMERIC(5, 2) DEFAULT \'NULL\', CHANGE current_issue_quantity current_issue_quantity INT DEFAULT NULL, CHANGE subcontractor_issue_quantity subcontractor_issue_quantity INT DEFAULT NULL, CHANGE degradation_quantity degradation_quantity INT DEFAULT NULL, CHANGE new_issue_quantity new_issue_quantity INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE report_contract CHANGE category_id category_id INT DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL, CHANGE mtbf mtbf NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE mttr mttr NUMERIC(10, 2) DEFAULT \'NULL\', CHANGE rate rate NUMERIC(5, 2) DEFAULT \'NULL\', CHANGE current_issue_quantity current_issue_quantity INT DEFAULT NULL, CHANGE subcontractor_issue_quantity subcontractor_issue_quantity INT DEFAULT NULL, CHANGE degradation_quantity degradation_quantity INT DEFAULT NULL, CHANGE new_issue_quantity new_issue_quantity INT DEFAULT NULL, CHANGE contractual_quantity contractual_quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE search CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE category_id category_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE software CHANGE brand_id brand_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subcontractor_repair CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE date_entry date_entry DATE DEFAULT \'NULL\', CHANGE date_return date_return DATE DEFAULT \'NULL\', CHANGE date_dispatch date_dispatch DATE DEFAULT \'NULL\', CHANGE rma rma VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE token CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL, CHANGE token_id token_id INT DEFAULT NULL');
    }
}
