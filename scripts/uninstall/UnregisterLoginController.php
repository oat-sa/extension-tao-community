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
 */

declare(strict_types=1);

namespace oat\taoCe\scripts\uninstall;

use oat\oatbox\extension\InstallAction;
use oat\tao\model\mvc\DefaultUrlService;

class UnregisterLoginController extends InstallAction
{
    public function __invoke($params)
    {
        $defaultUrlService = $this->getServiceManager()->get(DefaultUrlService::SERVICE_ID);
        $defaultUrlServiceLoginOption = $defaultUrlService->getOption('login');
        unset($defaultUrlServiceLoginOption['fallback']);
        $defaultUrlServiceLoginOption['ext'] = 'tao';
        $defaultUrlServiceLoginOption['controller'] = 'Main';
        $defaultUrlServiceLoginOption['action'] = 'login';
        $defaultUrlService->setOption('login', $defaultUrlServiceLoginOption);
        $this->getServiceManager()->register(DefaultUrlService::SERVICE_ID, $defaultUrlService);
    }
}
