<?php

declare(strict_types=1);

namespace oat\taoCe\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\tao\model\menu\SectionVisibilityFilter;
use oat\tao\scripts\tools\migrations\AbstractMigration;
use oat\taoCe\scripts\install\MapHelpSectionFeatureFlag;

/**
 * Auto-generated Migration: Please modify to your needs!
 *
 * phpcs:disable Squiz.Classes.ValidClassName
 */
final class Version202306151234563976_taoCe extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Map Help navigation Section Behind Feature Flag';
    }

    public function up(Schema $schema): void
    {
        $this->propagate(
            new MapHelpSectionFeatureFlag()
        )();
    }

    public function down(Schema $schema): void
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
}
