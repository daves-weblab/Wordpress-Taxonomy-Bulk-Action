<?php

/*
Plugin Name: Enhanced Media Library Bulk
Description: add the option to add Attachments to Media Categories using a bulk action
Author: David Riedl
Author URI: http://www.daves-weblab.com
Version: 0.2
*/

if(!class_exists('DWL_Bulk_Action')) {
    class DWL_Bulk_Action {
        private $path = '';

        // available actions array('name' => 'label')
        private $actions = array();
        // type of checkbox to map posts to (e.g. media, post, ...)
        private $map = '';
        // page the plugin operates on (e.g. upload.php, edit.php, ...)
        private $page = '';
        // custom prefix, if multiple bulks are being used
        private $prefix = '';
        // taxonomy slug this bulk works with
        private $taxonomy = '';
        // label of the Bulk Box
        private $label = '';

        public function __construct() {
            $this->path = dirname(__FILE__) . '/';

            // custom plugin configuration
            $this->actions = array(
                'add' => __('hinzufügen'),
                'remove' => __('entfernen')
            );

            $this->map = '';
            $this->page = '';
            $this->prefix = '';
            $this->taxonomy = '';
            $this->label = __('');

            if(is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
                add_action('admin_footer-' . $this->page, array($this, 'registerViews'));

                foreach($this->actions as $action => $label) {
                    add_action('admin_action_' . $this->prefix . '-' . $action, array($this, 'handleAction'));
                }
            }
        }

        public function enqueueAssets($page) {
            if($page == $this->page) {
                wp_enqueue_style('dwl-'. $this->prefix .'-plugin', plugin_dir_url(__FILE__) . 'assets/css/plugin.css');
                wp_enqueue_script('dwl-'. $this->prefix .'-plugin', plugin_dir_url(__FILE__) . 'assets/js/plugin.js');

                wp_localize_script('dwl-'. $this->prefix .'-plugin', 'plugin', array(
                    'prefix' => $this->prefix,
                    'map' => $this->map,
                    'taxonomy' => $this->taxonomy
                ));
            }
        }

        public function registerViews() {
            include($this->path . 'includes/frontend-views.php');
        }

        public function handleAction() {
            if(current_user_can('edit_posts')) {
                $action = $this->req('action');

                if ($action) {
                    // remove prefix
                    $action = str_replace($this->prefix . '-', '', $action);
                }

                if ($action && $this->req('posts') && $this->req('taxonomies')) {
                    $posts = array_map('intval', explode(',', $this->req('posts')));
                    $taxonomies = array_map('intval', explode(',', $this->req('taxonomies')));

                    foreach ($posts as $post) {
                        switch ($action) {
                            case 'add':
                                wp_set_post_terms($post, $taxonomies, $this->taxonomy);
                                break;

                            case 'remove':
                                wp_remove_object_terms($post, $taxonomies, $this->taxonomy);
                                break;
                        }
                    }
                }
            }

            wp_redirect(admin_url($this->page));
            exit();
        }

        private function req($key) {
            if(array_key_exists($key, $_REQUEST)) {
                return $_REQUEST[$key];
            }

            return null;
        }
    }
}

new DWL_Bulk_Action();

/**
 * file ends here
 * @author David Riedl <david.riedl@daves-weblab.com>
 */