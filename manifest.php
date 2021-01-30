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

return [
    'name' => 'taoCe',
    'label' => 'Community Edition',
    'description' => 'the Community Edition extension',
    'license' => 'GPL-2.0',
    'version' => '9.2.0',
    'author' => 'Open Assessment Technologies SA',
    'requires' => [
        'tao' => '>=36.1.0',
        'funcAcl' => '*',
        'taoItems' => '*',
        'taoQtiItem' => '*',
        'qtiItemPci' => '*',
        'taoTests' => '*',
        'taoQtiTest' => '*',
        'taoQtiTestPreviewer' => '*',
        'taoTestTaker' => '*',
        'taoGroups' => '*',
        'taoOutcomeUi' => '*',
        'taoOutcomeRds' => '*',
        'taoDeliveryRdf' => '*',
    ],
    'update' => 'oat\\taoCe\\scripts\\update\\Updater',
    'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoCeManager',
    'acl' => [
        ['grant', 'http://www.tao.lu/Ontologies/TAO.rdf#BackOfficeRole', ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'index']],
        ['grant', 'http://www.tao.lu/Ontologies/TAO.rdf#BackOfficeRole', ['ext' => 'taoCe', 'mod' => 'Home']],
        ['grant', 'http://www.tao.lu/Ontologies/generis.rdf#AnonymousRole', ['ext' => 'taoCe', 'mod' => 'Main', 'act' => 'rootEntry']],
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
        '/taoCe' => 'oat\\taoCe\\controller'
    ],
    'constants' => [
        # views directory
        "DIR_VIEWS" => dirname(__FILE__) . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR,

        #BASE URL (usually the domain root)
        'BASE_URL' => ROOT_URL . 'taoCe/',
    ]
];
