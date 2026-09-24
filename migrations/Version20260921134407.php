<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260921134407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE module_categorie ADD CONSTRAINT FK_131CC9FCAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_categorie ADD CONSTRAINT FK_131CC9FCBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (mail)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFAFC2B591');
        $this->addSql('ALTER TABLE module_categorie DROP FOREIGN KEY FK_131CC9FCAFC2B591');
        $this->addSql('ALTER TABLE module_categorie DROP FOREIGN KEY FK_131CC9FCBCF5E72D');
        $this->addSql('DROP INDEX UNIQ_IDENTIFIER_USERNAME ON user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON user (username)');
    }
}
