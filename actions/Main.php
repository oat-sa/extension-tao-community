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

namespace oat\taoCe\actions;

/**
 * Sample controller
 *
 * @author Open Assessment Technologies SA
 * @package taoCe
 * @subpackage actions
 * @license GPL-2.0
 *
 */
class Main extends \tao_actions_Main {

    /**
     * The user service
     * @var tao_models_classes_UserService 
     */
    private $userService;
    
    /**
     * initialize the services
     */
    public function __construct(){
        
        parent::__construct();
        $this->userService = \tao_models_classes_UserService::singleton();
    }

    /**
     * A possible entry point to tao
     */
    public function index() {
        
        parent::index();
        
        $user = $this->userService->getCurrentUser();
        $firsttime = $this->userService->isFirstTimeInTao($user);
        if($firsttime == false || $this->hasRequestParameter('nosplash')){
            $this->userService->becomeVeteran($user);
        }

        $defaultExtIds = array('items', 'tests', 'subjects', 'groups', 'delivery', 'results');
        $defaultExtensions = array();
        $additionalExtensions = array();
        foreach ($this->service->getAllStructures() as $i => $structure) {
            if ($structure['data']['visible'] == 'true') {
                $data = $structure['data'];
                if (in_array((string) $structure['id'], $defaultExtIds)) {
                    $defaultExtensions[strval($structure['id'])] = array(
                        'id' => (string) $structure['id'],
                        'name' => (string) $data['name'],
                        'extension' => $structure['extension'],
                        'description' => (string) $data->description
                    );
                } else {
                    $additionalExtensions[$i] = array(
                        'id' => (string) $structure['id'],
                        'name' => (string) $data['name'],
                        'extension' => $structure['extension'],
                        'description' => (string) $data->description
                    );
                }

                //Test if access
                $access = false;
                foreach ($data->sections->section as $section) {
                    list($ext, $mod, $act) = explode('/', trim((string) $section['url'], '/'));
                    if (\tao_models_classes_accessControl_AclProxy::hasAccess($ext, $mod, $act)) {
                        $access = true;
                        break;
                    }
                }
                if (in_array((string) $structure['id'], $defaultExtIds)) {
                    $defaultExtensions[strval($structure['id'])]['enabled'] = $access;
                } else {
                    $additionalExtensions[$i]['enabled'] = $access;
                }
            }
        }

        $this->setData('extensions', array_merge($defaultExtensions, $additionalExtensions));
        $this->setData('defaultExtensions', $defaultExtensions);
        $this->setData('additionalExtensions', $additionalExtensions);
    }

    
}