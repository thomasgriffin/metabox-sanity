<?php
/**
 * Metabox Sanity provides WordPress theme and plugin authors a way to
 * benevolently dictate how other plugins and themes can interact with
 * their custom post type interfaces in the form of metaboxes.
 *
 * @package   TGM-Metabox-Sanity
 * @version   1.0.0
 * @author    Thomas Griffin <thomasgriffinmedia.com>
 * @copyright Copyright (c) 2014, Thomas Griffin
 * @license   http://opensource.org/licenses/gpl-2.0.php GPL v2 or later
 * @link      https://github.com/thomasgriffin/metabox-sanity
 */

/*
    Copyright 2014 Thomas Griffin (thomasgriffinmedia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'TGM_Metabox_Sanity' ) ) {
    /**
     * Provides an intuitive way for plugin and theme authors to
     * limit what metaboxes can be displayed on their custom post
     * type interfaces.
     *
     * @since 1.0.0
     *
     * @package TGM-Metabox-Sanity
     * @author  Thomas Griffin <thomasgriffinmedia.com>
     */
    class TGM_Metabox_Sanity {

        /**
         * Holds the data to be used in the class
         *
         * @since 1.0.0
         *
         * @var array
         */
        public $data = array();

        /**
         * Creates the object and sets all the relevant class properties.
         *
         * @since 1.0.0
         *
         * @param array $config Array of config parameters.
         */
        public function __construct( array $config ) {

            // Set the data property based on the config passed.
            foreach ( $config as $post_type => $data ) {
                $this->data[$post_type] = $data;
            }

        }

        /**
         * Initializes and runs the metabox sanity goodness.
         *
         * @since 1.0.0
         *
         * @global array $wp_meta_boxes Array of registered WordPress metaboxes.
         */
        public function init() {

            // If there is no data, return.
            if ( empty( $this->data ) ) {
                return;
            }

            // Bring the global metaboxes array into scope.
            global $wp_meta_boxes;

            // Loop through each post type and start whitelisting metaboxes!
            foreach ( $this->data as $post_type => $data ) {
                // If no contexts or priorities have been specified, do nothing.
                if ( empty( $data['contexts'] ) || empty( $data['priorities'] ) ) {
                    continue;
                }

                // Now loop through each context that has been assigned to the post type.
                foreach ( (array) $data['contexts'] as $context ) {
                    // Now loop through each priority within the context.
                    foreach ( (array) $data['priorities'] as $priority ) {
                        if ( isset( $wp_meta_boxes[$post_type][$context][$priority] ) ) {
                            // Loop through each priority and check the data to determine whether the metabox will stay or go.
                            foreach ( (array) $wp_meta_boxes[$post_type][$context][$priority] as $id => $metabox_data ) {
                                // If the metabox ID matches one to pass over, whitelist it and allow it to be registered.
                                if ( in_array( $id, (array) $data['whitelist'] ) ) {
                                    unset( $data['whitelist'][$id] );
                                    continue;
                                }

                                // Otherwise, loop over the IDs to skip and see if there is a relevant match to whitelist.
                                foreach ( (array) $data['whitelist'] as $skip ) {
                                    if ( preg_match( '#^' . $id . '#i', $skip ) ) {
                                        continue;
                                    }
                                }

                                // The metabox is not allowed on this screen. Prevent it from being registered.
                                unset( $wp_meta_boxes[$post_type][$context][$priority][$id] );
                            }
                        }
                    }
                }
            }

        }

    }

}