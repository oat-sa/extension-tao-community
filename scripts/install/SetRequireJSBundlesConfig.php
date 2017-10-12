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
 * Copyright (c) 2017 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 */

namespace oat\taoCe\scripts\install;

use oat\oatbox\extension\InstallAction;

/**
 * This declares bundling options for requirejs to the extensions configuration
 */
class SetRequireJSBundlesConfig extends InstallAction
{
    public function __invoke($params)
    {
        $ext = common_ext_ExtensionsManager::singleton()->getExtensionById('taoCe');
        $ext->setConfig('requirejsbundles', array(
            array(
                'name' => 'taocebundle',
                'path' => ROOT_URL . 'taoCe/views/dist/controllers.min',
                'modules' => array(
                    'taoCe/controller/help',
                    'taoCe/controller/home',
                    'taoCe/controller/home/custom-scrollbar',
                    'taoCe/controller/home/splash',
                    'taoCe/controller/routes',
                ),
            ),
        ));
    }
}
