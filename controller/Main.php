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
use tao_actions_Main as MainAction;
use oat\tao\model\mvc\DefaultUrlService;
use common_session_SessionManager as SessionManager;

class Main extends MainAction
{
    /**
     * Wrapper to the main action: update the first time property and redirect
     */
    public function index(): void
    {
        $this->defaultData();
        $queryParams = $this->getPsrRequest()->getQueryParams();

        if (isset($queryParams['ext']) || isset($queryParams['structure'])) {
            if (isset($queryParams['nosplash'])) {
                TaoCe::becomeVeteran();
            }

            $this->redirect($this->getUrlService()->getDefaultUrl([
                'ext' => $queryParams['ext'] ?? null,
                'structure' => $queryParams['structure'] ?? null,
            ]));
        } else {
            parent::index();
        }
    }

    /**
     * Redirect request made to root of tao.
     */
    public function rootEntry(): void
    {
        $this->defaultData();

        if (SessionManager::isAnonymous()) {
            $this->redirect($this->getUrlService()->getLoginUrl());
        } else {
            $this->redirect(_url('entry', 'Main', 'tao'));
        }
    }

    private function getUrlService(): DefaultUrlService
    {
        return $this->getServiceLocator()->get(DefaultUrlService::SERVICE_ID);
    }
}
