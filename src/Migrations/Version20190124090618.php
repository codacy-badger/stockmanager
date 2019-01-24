<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190124090618 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE category_symptom');
        $this->addSql('DROP TABLE user_authorization');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD hours_per_day INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT NULL, CHANGE date_ready date_ready DATETIME DEFAULT NULL, CHANGE date_end date_end DATETIME DEFAULT NULL, CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE location ADD is_ok TINYINT(1) DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE reference reference VARCHAR(255) DEFAULT NULL, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT NULL, CHANGE degradation degradation TINYINT(1) DEFAULT NULL, CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT NULL, CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT NULL, CHANGE soft_upload soft_upload TINYINT(1) DEFAULT NULL, CHANGE soft_version soft_version VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_symptom (category_id INT NOT NULL, symptom_id INT NOT NULL, INDEX IDX_231AB0DE12469DE2 (category_id), INDEX IDX_231AB0DEDEEFDA95 (symptom_id), PRIMARY KEY(category_id, symptom_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_authorization (user_id INT NOT NULL, authorization_id INT NOT NULL, INDEX IDX_94E326BFA76ED395 (user_id), INDEX IDX_94E326BF2F8B0EB2 (authorization_id), PRIMARY KEY(user_id, authorization_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_symptom ADD CONSTRAINT FK_231AB0DE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_symptom ADD CONSTRAINT FK_231AB0DEDEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_authorization ADD CONSTRAINT FK_94E326BF2F8B0EB2 FOREIGN KEY (authorization_id) REFERENCES authorization (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_authorization ADD CONSTRAINT FK_94E326BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE brand CHANGE category_id category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category DROP hours_per_day, CHANGE image_id image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE equipment CHANGE brand_id brand_id INT DEFAULT NULL, CHANGE contract_id contract_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE issue CHANGE user_id user_id INT DEFAULT NULL, CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE equipment_replace_id equipment_replace_id INT DEFAULT NULL, CHANGE repair_id repair_id INT DEFAULT NULL, CHANGE transportation_id transportation_id INT DEFAULT NULL, CHANGE delivery_id delivery_id INT DEFAULT NULL, CHANGE date_checked date_checked DATETIME DEFAULT \'NULL\', CHANGE date_ready date_ready DATETIME DEFAULT \'NULL\', CHANGE date_end date_end DATETIME DEFAULT \'NULL\', CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE location DROP is_ok, CHANGE equipment_id equipment_id INT DEFAULT NULL, CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message CHANGE date_message date_message DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE operator CHANGE site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE part CHANGE reference reference VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE repair_time repair_time INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repair CHANGE technician_id technician_id INT DEFAULT NULL, CHANGE image_id image_id INT DEFAULT NULL, CHANGE end_date end_date DATETIME DEFAULT \'NULL\', CHANGE degradation degradation TINYINT(1) DEFAULT \'NULL\', CHANGE no_breakdown no_breakdown TINYINT(1) DEFAULT \'NULL\', CHANGE time_to_repair time_to_repair INT DEFAULT NULL, CHANGE stats_download stats_download TINYINT(1) DEFAULT \'NULL\', CHANGE soft_upload soft_upload TINYINT(1) DEFAULT \'NULL\', CHANGE soft_version soft_version VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE site CHANGE adress adress VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE postal postal INT DEFAULT NULL, CHANGE city city VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE symptom CHANGE position position SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE authorization_id authorization_id INT DEFAULT NULL, CHANGE operator_id operator_id INT DEFAULT NULL');
    }
}
