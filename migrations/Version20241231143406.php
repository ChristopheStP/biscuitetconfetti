<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231143406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Convertir d'abord les données existantes en JSON valide
        $this->addSql('UPDATE product SET illustration = JSON_ARRAY(illustration) WHERE illustration IS NOT NULL');
        
        // Ensuite changer le type de colonne
        $this->addSql('ALTER TABLE product CHANGE illustration illustration JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product CHANGE illustration illustration VARCHAR(255) NOT NULL');
    }
}
