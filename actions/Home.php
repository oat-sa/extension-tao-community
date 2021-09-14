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
 * Copyright (c) 2014-2021 (original work) Open Assessment Technologies SA;
 */

declare(strict_types=1);

namespace oat\taoCe\actions;

use oat\tao\helpers\TaoCe;
use tao_actions_CommonModule;
use oat\tao\model\menu\MenuService;
use oat\tao\model\menu\Perspective;
use tao_models_classes_accessControl_AclProxy;

/** @author Bertrand Chevrier <bertrand@taotesting.com> */
class Home extends tao_actions_CommonModule
{
    /** This action renders the template used by the splash screen popup */
    public function splash(): void
    {
        // The list of extensions the splash provides an explanation for.
        $defaultExtIds = ['items', 'tests', 'TestTaker', 'groups', 'delivery', 'results'];

        // Check if the user is a noob
        $this->setData('firstTime', TaoCe::isFirstTimeInTao());

        //load the extension data
        $defaultExtensions = [];
        $additionalExtensions = [];

        /** @var Perspective $perspective */
        foreach (MenuService::getPerspectivesByGroup(Perspective::GROUP_DEFAULT) as $perspective) {
            $perspectiveId = (string) $perspective->getId();
            $access = false;

            foreach ($perspective->getChildren() as $section) {
                $hasAccess = tao_models_classes_accessControl_AclProxy::hasAccess(
                    $section->getAction(),
                    $section->getController(),
                    $section->getExtensionId()
                );

                if ($hasAccess) {
                    $access = true;
                    break;
                }
            }

            $extensionInfo = [
                'id' => $perspectiveId,
                'name' => $perspective->getName(),
                'extension' => $perspective->getExtension(),
                'enabled' => $access,
            ];

            if (in_array($perspectiveId, $defaultExtIds, true)) {
                $extensionInfo['description'] = $perspective->getDescription();
                $defaultExtensions[$perspectiveId] = $extensionInfo;
            } else {
                $additionalExtensions[$perspectiveId] = $extensionInfo;
            }
        }

        $this->setData('extensions', array_merge($defaultExtensions, $additionalExtensions));
        $this->setData('defaultExtensions', $defaultExtensions);
        $this->setData('additionalExtensions', $additionalExtensions);

        $this->setView('splash.tpl');
    }
}
