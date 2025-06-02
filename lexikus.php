<?php
/*
Plugin Name: Lexikus
Description: Création d'un lexique et liaison automatique des termes du lexique vers leur définition dans WordPress.
Version: 0.1.0
Author: JF Minatchy
Author URI: https://github.com/jfminatchy
Text Domain: lexikus
*/

if (!defined('ABSPATH')) exit;

// Chargement des fichiers nécessaires
require_once plugin_dir_path(__FILE__) . 'includes/settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/linker.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';
}
