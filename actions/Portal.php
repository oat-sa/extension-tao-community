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
 * Copyright (c) 2023 (original work) Open Assessment Technologies SA;
 *
 *
 */

namespace oat\taoCe\actions;

use common_ext_ExtensionsManager;
use oat\tao\helpers\TaoCe;
use oat\tao\model\mvc\DefaultUrlService;

/**
 * Just to override the paths and load the specific client side code
 * @author Bertrand Chevrier <bertrand@taotesting.com>
 * @package taoCe

 * @license GPL-2.0
 *
 */
class Portal extends \tao_actions_Main
{

    /**
     * Wrapper to the main action: update the first time property and redirect
     * @return void
     */
    public function login()
    {
        if (isset($_ENV['PORTAL_LOGIN_URL'])) {
            $this->redirect($_ENV['PORTAL_LOGIN_URL']);
            return;
        }

        $defaultUrlService = $this->getServiceLocator()->get(DefaultUrlService::SERVICE_ID);
        $fallback = $defaultUrlService->getOption('login')['fallback'];

        return $this->redirect(_url($fallback['action'], $fallback['controller'], $fallback['ext']));
    }
}
