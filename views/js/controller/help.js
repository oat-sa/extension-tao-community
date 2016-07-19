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
define([
    'jquery',
    'util/url',
    'taoCe/tree',
    'json!taoCe/resources/trees/matches.json'
], function($, url, Tree, matches){
    'use strict';
    var $mainContainer = $('body');

    var $popup = $();

    /**
     *
     * @returns {string}
     */
    var getHelpUrl = function () {
        var urlData = url.parse(window.location);
        var params = urlData.query;
        var baseUrl = urlData.protocol + '://' + urlData.authority + '/taoCe/views/js/resources/html';

        var $action = $('.action.active');

        if (!$action.length) {
            return baseUrl + '/error/404.html';
        }

        var branchId = params.structure + '/' + params.section + '/' + $action.attr('id');
        var matchTree = Tree(matches);
        var branch = matchTree.getBranch(branchId);

        if (!branch.ug) {
            return baseUrl + '/error/404.html';
        }

        return baseUrl + '/' + branch.ug.url;
    };

    /**
     *
     */
    var updateHelp = function () {
        if (!$popup.length) {
            return;
        }
        $popup.find('iframe').attr('src', getHelpUrl());
    };

    /**
     *
     * @param url
     */
    var popup = function (url) {

        var headerHeight = $('header.dark-bar').outerHeight(),
            footerHeight = $('footer.dark-bar').outerHeight(),
            width = window.innerWidth / 2;

        $popup = $('#ugHelpWindow');
        if ($popup.length) {
            $popup.find('iframe').attr('src', url);
            $popup.show();
            return;
        }


        $popup = $('<div>', {
            id: 'ugHelpWindow'
        }).css({
                position: 'absolute',
                left: width,
                top: headerHeight,
                width: width,
                height: window.innerHeight - headerHeight - footerHeight,
                zIndex: 1000000
            }
        );


        var $closer = $('<span class="icon-close"/>').css({
            position: 'absolute',
            right: '40px',
            top: '20px',
            fontSize: '3rem',
            cursor: 'pointer'
        }).on('click', function () {
            $popup.hide();
        });

        var $iframe = $('<iframe>', {
            src: url,
            width: '100%',
            height: '100%'
        });
        $popup.append($closer, $iframe);
        $mainContainer.append($popup);
    };

    /**
     * The helpController sets up the help window
     *
     * @exports taoCe/controller/help
     */
    var helpController = {

        /**
         * Setup the splash screen: loads it's content and initialize the component.
         */
        start: function start() {

            popup(getHelpUrl());

            $(document).on('click', '.action', function () {
                updateHelp();
            });
        }
    };

    return helpController;
});
