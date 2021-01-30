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

namespace oat\taoCe\controller;

use oat\tao\helpers\TaoCe;
use oat\tao\model\menu\MenuService;
use oat\tao\model\menu\Perspective;
use tao_actions_CommonModule as CommonModule;
use oat\tao\model\accessControl\ActionResolver;

class Home extends CommonModule
{
    private const DEFAULT_EXTENSION_IDS = [
        'items',
        'tests',
        'TestTaker',
        'groups',
        'delivery',
        'results',
    ];

    /**
     * Renders the template used by the splash screen popup
     */
    public function splash(): void
    {
        $this->setData('firstTime', TaoCe::isFirstTimeInTao());

        $defaultExtensions = [];
        $additionalExtensions = [];

        /** @var Perspective $perspective */
        foreach (MenuService::getPerspectivesByGroup(Perspective::GROUP_DEFAULT) as $i => $perspective) {
            $isDefaultExtension = $this->isDefaultExtension($perspective);
            $hasAccess = $this->checkUserAccessToExtension($perspective);
            $extension = $this->extensionToArray($perspective, $isDefaultExtension, $hasAccess);

            if ($isDefaultExtension) {
                $defaultExtensions[(string) $perspective->getId()] = $extension;
            } else {
                $additionalExtensions[$i] = $extension;
            }
        }

        $this->setData('extensions', array_merge($defaultExtensions, $additionalExtensions));
        $this->setData('defaultExtensions', $defaultExtensions);
        $this->setData('additionalExtensions', $additionalExtensions);

        $this->setView('splash.tpl');
    }

    private function isDefaultExtension(Perspective $extension): bool
    {
        return in_array((string) $extension->getId(), self::DEFAULT_EXTENSION_IDS, true);
    }

    private function checkUserAccessToExtension(Perspective $extension): bool
    {
        foreach ($extension->getChildren() as $section) {
            $resolver = ActionResolver::getByControllerName($section->getController(), $section->getExtensionId());

            if ($this->hasAccess($resolver->getController(), $section->getAction())) {
                return true;
            }
        }

        return false;
    }

    private function extensionToArray(Perspective $extension, bool $isDefault, bool $hasAccess): array
    {
        $data = [
            'id' => $extension->getId(),
            'name' => $extension->getName(),
            'extension' => $extension->getExtension(),
            'enabled' => $hasAccess,
        ];

        if ($isDefault) {
            $data['description'] = $extension->getDescription();
        }

        return $data;
    }
}
