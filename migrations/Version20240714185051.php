<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240714185051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE goal (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, project_link VARCHAR(255) DEFAULT NULL, source_code_link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_goal (project_id INT NOT NULL, goal_id INT NOT NULL, INDEX IDX_C54D2336166D1F9C (project_id), INDEX IDX_C54D2336667D1AFE (goal_id), PRIMARY KEY(project_id, goal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_stack (project_id INT NOT NULL, stack_id INT NOT NULL, INDEX IDX_52FD72F4166D1F9C (project_id), INDEX IDX_52FD72F437C70060 (stack_id), PRIMARY KEY(project_id, stack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill_stack (skill_id INT NOT NULL, stack_id INT NOT NULL, INDEX IDX_6480E8565585C142 (skill_id), INDEX IDX_6480E85637C70060 (stack_id), PRIMARY KEY(skill_id, stack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stack (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, icon VARCHAR(255) DEFAULT NULL, color VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_goal ADD CONSTRAINT FK_C54D2336166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_goal ADD CONSTRAINT FK_C54D2336667D1AFE FOREIGN KEY (goal_id) REFERENCES goal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_stack ADD CONSTRAINT FK_52FD72F4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE project_stack ADD CONSTRAINT FK_52FD72F437C70060 FOREIGN KEY (stack_id) REFERENCES stack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_stack ADD CONSTRAINT FK_6480E8565585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE skill_stack ADD CONSTRAINT FK_6480E85637C70060 FOREIGN KEY (stack_id) REFERENCES stack (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_goal DROP FOREIGN KEY FK_C54D2336166D1F9C');
        $this->addSql('ALTER TABLE project_goal DROP FOREIGN KEY FK_C54D2336667D1AFE');
        $this->addSql('ALTER TABLE project_stack DROP FOREIGN KEY FK_52FD72F4166D1F9C');
        $this->addSql('ALTER TABLE project_stack DROP FOREIGN KEY FK_52FD72F437C70060');
        $this->addSql('ALTER TABLE skill_stack DROP FOREIGN KEY FK_6480E8565585C142');
        $this->addSql('ALTER TABLE skill_stack DROP FOREIGN KEY FK_6480E85637C70060');
        $this->addSql('DROP TABLE goal');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_goal');
        $this->addSql('DROP TABLE project_stack');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE skill_stack');
        $this->addSql('DROP TABLE stack');
    }
}
