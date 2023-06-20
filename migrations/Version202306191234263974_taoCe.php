<?php

declare(strict_types=1);

namespace oat\taoCe\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\oatbox\reporting\Report;
use oat\tao\model\accessControl\func\AccessRule;
use oat\tao\model\accessControl\func\AclProxy;
use oat\tao\model\mvc\DefaultUrlService;
use oat\tao\model\user\TaoRoles;
use oat\tao\scripts\tools\migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 *
 * phpcs:disable Squiz.Classes.ValidClassName
 */
final class Version202306191234263974_taoCe extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Grant access to Login Controller';
    }

    public function up(Schema $schema): void
    {
        AclProxy::applyRule($this->getAccessRule());

        $this->registerLoginController();
    }

    public function down(Schema $schema): void
    {
        AclProxy::revokeRule($this->getAccessRule());

        $this->unregisterLoginController();
    }

    /**
     * @return AccessRule
     */
    private function getAccessRule(): AccessRule
    {
        return new AccessRule(
            AccessRule::GRANT,
            TaoRoles::ANONYMOUS,
            ['ext' => 'taoCe', 'mod' => 'Login', 'act' => 'login']
        );
    }

    public function registerLoginController(): void
    {
        $this->addReport(
            Report::createInfo(
                'Registering Login Controller'
            )
        );

        $service = $this->getServiceLocator()->get(DefaultUrlService::SERVICE_ID);
        $loginOption = $service->getOption('login');

        $loginOption['fallback'] = $loginOption;
        $loginOption['ext'] = 'taoCe';
        $loginOption['controller'] = 'Login';
        $loginOption['action'] = 'login';

        $service->setOption('login', $loginOption);

        $this->getServiceLocator()->register(DefaultUrlService::SERVICE_ID, $service);
    }


    public function unregisterLoginController(): void
    {
        $this->addReport(
            Report::createInfo(
                'Unregistering Login Controller'
            )
        );

        $service = $this->getServiceLocator()->get(DefaultUrlService::SERVICE_ID);
        $loginOption = $service->getOption('login');
        $fallbackOption = $loginOption['fallback'];

        $loginOption['ext'] = $fallbackOption['ext'];
        $loginOption['controller'] = $fallbackOption['controller'];
        $loginOption['action'] = $fallbackOption['action'];

        unset($loginOption['fallback']);

        $service->setOption('login', $loginOption);

        $this->getServiceLocator()->register(DefaultUrlService::SERVICE_ID, $service);
    }
}
