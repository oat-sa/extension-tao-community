/*
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General License for more details.
 *
 * You should have received a copy of the GNU General License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA ;
 *
 */

/**
 * This module handles trees
 *
 */
define(function () {

    'use strict';

    /**
     * Tree factory
     *
     * @param {Object} _tree
     * @returns {{appendBranch: appendBranch, getBranch: getBranch, getTree: getTree, insertBranch: insertBranch, prependBranch: prependBranch, removeBranch: removeBranch}}
     */
    return function (_tree) {

        var tree = _tree;

        return {
            /**
             * Adds a new branch at the end of the tree.
             * This will overwrite an existing branch with the same id.
             *
             * @param {String} branchId
             * @param {Object} branch
             * @returns {Object} chain
             */
            appendBranch: function appendBranch(branchId, branch) {
                this.removeBranch(branchId);
                tree[branchId] = branch;
                return this;
            },

            /**
             * Returns the value array of said branch or an empty array on failure
             *
             * @param {String} branchId
             * @return {Object} {branch|{}}
             */
            getBranch: function getBranch(branchId) {
                return tree[branchId] ? tree[branchId] : {};
            },

            /**
             * Exposes data
             *
             * @returns {tree|{}}
             */
            getTree: function getTree() {
                return tree;
            },

            /**
             * Adds a new branch in the alphabetically correct position.
             * This will overwrite an existing branch with the same id.
             *
             * @param {String} branchId
             * @param {Object} branch
             * @returns {Object} chain
             */
            insertBranch: function insertBranch(branchId, branch) {
                var _tree = {};
                this.appendBranch(branchId, branch);
                Object.keys(tree).sort(function (a, b) {
                    return a.toLowerCase() > b.toLowerCase();
                }).forEach(function (branchId) {
                    _tree[branchId] = tree[branchId];
                });
                tree = _tree;
                return this;
            },

            /**
             * Adds a new branch at the beginning of the tree.
             * This will overwrite an existing branch with the same id.
             *
             * @param {String} branchId
             * @param {Object} branch
             * @returns {Object} chain
             */
            prependBranch: function prependBranch(branchId, branch) {
                var _tree = {}, _branchId;
                this.removeBranch(branchId);
                _tree[branchId] = branch;
                for (_branchId in tree) {
                    if (tree.hasOwnProperty(_branchId)) {
                        _tree[_branchId] = tree[_branchId];
                    }
                }
                tree = _tree;
                return this;
            },

            /**
             * Removes a branch from the tree
             *
             * @param {String} branchId
             * @returns {Object} chain
             */
            removeBranch: function removeBranch(branchId) {
                delete tree[branchId];
                return this;
            }
        }
    };
});
