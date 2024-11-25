<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241125202202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_statistic DROP FOREIGN KEY FK_E3CBE4A781877935');
        $this->addSql('DROP INDEX IDX_E3CBE4A781877935 ON character_statistic');
        $this->addSql('ALTER TABLE character_statistic ADD character_id INT NOT NULL, ADD statistic VARCHAR(255) NOT NULL, DROP character_id_id, DROP stat_id');
        $this->addSql('ALTER TABLE character_statistic ADD CONSTRAINT FK_E3CBE4A71136BE75 FOREIGN KEY (character_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_E3CBE4A71136BE75 ON character_statistic (character_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_statistic DROP FOREIGN KEY FK_E3CBE4A71136BE75');
        $this->addSql('DROP INDEX IDX_E3CBE4A71136BE75 ON character_statistic');
        $this->addSql('ALTER TABLE character_statistic ADD stat_id INT NOT NULL, DROP statistic, CHANGE character_id character_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE character_statistic ADD CONSTRAINT FK_E3CBE4A781877935 FOREIGN KEY (character_id_id) REFERENCES `character` (id)');
        $this->addSql('CREATE INDEX IDX_E3CBE4A781877935 ON character_statistic (character_id_id)');
    }
}
