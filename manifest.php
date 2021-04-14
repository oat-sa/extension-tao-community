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
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA;
 *
 *
 */

use oat\taoItems\model\user\TaoItemsRoles;
use oat\tao\model\accessControl\func\AccessRule;

return [
    'name' => 'taoCe',
    'label' => 'Community Edition',
    'description' => 'the Community Edition extension',
    'license' => 'GPL-2.0',
    'author' => 'Open Assessment Technologies SA',
    'update' => 'oat\\taoCe\\scripts\\update\\Updater',
    'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoCeManager',
    'acl' => [
        ['grant', 'http://www.tao.lu/Ontologies/TAO.rdf#BackOfficeRole', ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'index']],
        ['grant', 'http://www.tao.lu/Ontologies/TAO.rdf#BackOfficeRole', ['ext' => 'taoCe', 'mod' => 'Home']],
        ['grant', 'http://www.tao.lu/Ontologies/generis.rdf#AnonymousRole', ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'rootEntry']],
        [
            AccessRule::GRANT,
            TaoItemsRoles::ITEM_CLASS_NAVIGATOR,
            ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'index'],
        ],
        [
            AccessRule::GRANT,
            TaoItemsRoles::ITEM_CLASS_NAVIGATOR,
            ['ext' => 'taoCe', 'mod' => 'Home'],
        ],
    ],
    'install' => [
        'php' => [
            dirname(__FILE__) . '/scripts/install/overrideEntryPoint.php',
        ]
    ],
    'uninstall' => [
    ],
    'routes' => [
        '' => ['class' => 'oat\\taoCe\\model\\routing\\EntryRoute'],
        '/taoCe' => 'oat\\taoCe\\actions'
    ],
    'constants' => [
        # views directory
        "DIR_VIEWS" => dirname(__FILE__) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR,

        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL . 'taoCe/',
    ]
];
