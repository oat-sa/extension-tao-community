<?php

declare(strict_types=1);

namespace oat\taoCe\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\tao\model\menu\SectionVisibilityFilter;
use oat\tao\scripts\tools\migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 *
 * phpcs:disable Squiz.Classes.ValidClassName
 */
final class Version202403211025043974_taoCe extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $sectionVisibilityFilter = $this->getServiceManager()->get(SectionVisibilityFilter::SERVICE_ID);
        $featureFlagSections = $sectionVisibilityFilter
            ->getOption(SectionVisibilityFilter::OPTION_FEATURE_FLAG_SECTIONS);

        unset($featureFlagSections['help']);
        $sectionVisibilityFilter->setOption(
            SectionVisibilityFilter::OPTION_FEATURE_FLAG_SECTIONS,
            $featureFlagSections
        );

        $this->getServiceManager()->register(SectionVisibilityFilter::SERVICE_ID, $sectionVisibilityFilter);
    }

    public function down(Schema $schema): void
    {
        $this->throwIrreversibleMigrationException();
    }
}
