<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008132721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donate DROP FOREIGN KEY FK_DDA20471A76ED395');
        $this->addSql('DROP INDEX IDX_DDA20471A76ED395 ON donate');
        $this->addSql('ALTER TABLE donate CHANGE user_id restaurant_id INT NOT NULL');
        $this->addSql('ALTER TABLE donate ADD CONSTRAINT FK_DDA20471B1E7706E FOREIGN KEY (restaurant_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DDA20471B1E7706E ON donate (restaurant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donate DROP FOREIGN KEY FK_DDA20471B1E7706E');
        $this->addSql('DROP INDEX IDX_DDA20471B1E7706E ON donate');
        $this->addSql('ALTER TABLE donate CHANGE restaurant_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE donate ADD CONSTRAINT FK_DDA20471A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DDA20471A76ED395 ON donate (user_id)');
    }
}
