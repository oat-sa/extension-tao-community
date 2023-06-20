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

declare(strict_types=1);

namespace oat\taoCe\actions;

use oat\tao\model\mvc\DefaultUrlService;

class Login extends \tao_actions_Main
{
    public function login()
    {
        if (isset($_ENV['TAO_LOGIN_URL'])) {
            return $this->redirect($_ENV['TAO_LOGIN_URL']);
        }

        $defaultUrlService = $this->getServiceLocator()->get(DefaultUrlService::SERVICE_ID);
        $fallback = $defaultUrlService->getOption('login')['fallback'];

        return $this->redirect(_url($fallback['action'], $fallback['controller'], $fallback['ext']));
    }
}
