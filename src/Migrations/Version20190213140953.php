<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190213140953 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mouvement');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT NULL, CHANGE date_ready date_ready DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD part1_id INT DEFAULT NULL, ADD part2_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT NULL, CHANGE is_ok is_ok TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB7F9C608B FOREIGN KEY (part1_id) REFERENCES part (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB6D29CF65 FOREIGN KEY (part2_id) REFERENCES part (id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB7F9C608B ON location (part1_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB6D29CF65 ON location (part2_id)');
        $this->addSql('ALTER TABLE mass_processing CHANGE treated treated INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE part_group_id part_group_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_group CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE issue_id issue_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT NULL, CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT NULL, CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT NULL, CHANGE soft_upload soft_upload TINYINT(1) DEFAULT NULL, CHANGE soft_version soft_version VARCHAR(255) DEFAULT NULL, CHANGE unavailability unavailability INT DEFAULT NULL, CHANGE is_going_to_subcontractor is_going_to_subcontractor TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE subcontractor_repair CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE date_entry date_entry DATE DEFAULT NULL, CHANGE date_dispatch date_dispatch DATE DEFAULT NULL, CHANGE date_return date_return DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mouvement (MVIDMouvement INT NOT NULL, MVDate DATE DEFAULT \'NULL\', MVDateEnregistrement DATETIME DEFAULT \'NULL\', MVIDUser TINYINT(1) NOT NULL, MVEtat VARCHAR(2) DEFAULT \'NULL\' COLLATE utf8_general_ci, MVCommentaire TEXT DEFAULT NULL COLLATE utf8_general_ci, MVCommentaire2 TEXT DEFAULT NULL COLLATE utf8_general_ci, MVCommentClient TEXT DEFAULT NULL COLLATE utf8_general_ci, MVFichier_BL VARCHAR(512) DEFAULT \'NULL\' COLLATE utf8_general_ci, MVVersionLogicielle VARCHAR(10) DEFAULT \'NULL\' COLLATE utf8_general_ci, MVVersionBuild INT NOT NULL, MVNumSerie VARCHAR(30) DEFAULT \'NULL\' COLLATE utf8_general_ci, MVIDSite VARCHAR(10) DEFAULT \'NULL\' COLLATE utf8_general_ci, MVCodeDescriptionEtat TINYINT(1) DEFAULT \'NULL\', MVCodeDescriptionEtat2 TINYINT(1) DEFAULT \'NULL\', MVNoPanne TINYINT(1) DEFAULT \'0\' NOT NULL, MVCodeDiagnostic TINYINT(1) DEFAULT \'NULL\', MVCodeDiagnostic2 TINYINT(1) DEFAULT \'NULL\', MVSoft TINYINT(1) DEFAULT \'0\' NOT NULL, MVStats TINYINT(1) DEFAULT \'0\' NOT NULL, MVDegradation TINYINT(1) DEFAULT \'0\' NOT NULL, MVRepaSiteOise TINYINT(1) DEFAULT \'0\' NOT NULL, MVRepaVix TINYINT(1) DEFAULT \'0\' NOT NULL, MVParam TINYINT(1) DEFAULT \'0\' NOT NULL, MVTempsRepa INT DEFAULT NULL, IdEquipment INT NOT NULL, state TINYINT(1) NOT NULL, INDEX WDIDX_MOUVEMENT_MVIDSite (MVIDSite), INDEX WDIDX_MOUVEMENT_MVCodeDescriptionEtat (MVCodeDescriptionEtat), INDEX WDIDX_MOUVEMENT_MVDate (MVDate), INDEX WDIDX_MOUVEMENT_MVEtat (MVEtat), INDEX WDIDX_MOUVEMENT_MVNumSerie (MVNumSerie), PRIMARY KEY(MVIDMouvement)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category CHANGE image_id image_id INT DEFAULT NULL, CHANGE hours_per_day hours_per_day INT DEFAULT NULL, CHANGE mtbf mtbf INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT \'NULL\', CHANGE date_ready date_ready DATETIME DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB7F9C608B');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB6D29CF65');
        $this->addSql('DROP INDEX IDX_5E9E89CB7F9C608B ON location');
        $this->addSql('DROP INDEX IDX_5E9E89CB6D29CF65 ON location');
        $this->addSql('ALTER TABLE location DROP part1_id, DROP part2_id, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL, CHANGE date date DATE DEFAULT \'NULL\', CHANGE is_ok is_ok TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE mass_processing CHANGE treated treated INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE part_group_id part_group_id INT DEFAULT NULL, CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part_group CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE issue_id issue_id INT DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT \'NULL\', CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT \'NULL\', CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT \'NULL\', CHANGE soft_upload soft_upload TINYINT(1) DEFAULT \'NULL\', CHANGE soft_version soft_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE unavailability unavailability INT DEFAULT NULL, CHANGE is_going_to_subcontractor is_going_to_subcontractor TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE subcontractor_repair CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE date_entry date_entry DATE DEFAULT \'NULL\', CHANGE date_return date_return DATE DEFAULT \'NULL\', CHANGE date_dispatch date_dispatch DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
    }
}
