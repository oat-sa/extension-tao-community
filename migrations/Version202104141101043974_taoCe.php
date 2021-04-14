<?php

/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2021 (original work) Open Assessment Technologies SA;
 */

declare(strict_types=1);

namespace oat\taoCe\migrations;

use Doctrine\DBAL\Schema\Schema;
use oat\taoItems\model\user\TaoItemsRoles;
use oat\tao\model\accessControl\func\AclProxy;
use oat\tao\model\accessControl\func\AccessRule;
use oat\tao\scripts\tools\migrations\AbstractMigration;

final class Version202104141101043974_taoCe extends AbstractMigration
{
    private const RULES = [
        ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'index'],
        ['ext' => 'taoCe', 'mod' => 'Home'],
    ];

    public function getDescription(): string
    {
        return 'Apply rules to Item Class Navigator role';
    }

    public function up(Schema $schema): void
    {
        foreach (self::RULES as $rule) {
            AclProxy::applyRule($this->createAclRulesForRole(TaoItemsRoles::ITEM_CLASS_NAVIGATOR, $rule));
        }
    }

    public function down(Schema $schema): void
    {
        foreach (self::RULES as $rule) {
            AclProxy::revokeRule($this->createAclRulesForRole(TaoItemsRoles::ITEM_CLASS_NAVIGATOR, $rule));
        }
    }

    private function createAclRulesForRole(string $role, array $rule): AccessRule
    {
        return new AccessRule(
            AccessRule::GRANT,
            $role,
            $rule
        );
    }
}
