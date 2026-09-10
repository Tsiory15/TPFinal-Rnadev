<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260907074004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('DROP INDEX IDX_C242628BCF5E72D ON module');
        $this->addSql('ALTER TABLE module DROP categorie_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFAFC2B591');
        $this->addSql('ALTER TABLE module ADD categorie_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_C242628BCF5E72D ON module (categorie_id)');
    }
}
