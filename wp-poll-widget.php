<?php
/**
 * Plugin Name: Wordpress Poll Widget
 * Plugin URI: http://www.mystro-ken.cm/
 * Description: Create and manage a poll then place it on a widget area
 * Version: 1.0.3
 * Author: Emmanuel KWENE
 * Author URI: http://www.mystro-ken.cm/
 * Licence: Copyright (c) Juillet 2015 (njume48@gmail.com)
 */

// Constants
//==============================================================

/* Configuration */
if( !defined('APPLEMANIAK_SONDAGE_VERSION') )         define('APPLEMANIAK_SONDAGE_VERSION',      '1.0.3');
if( !defined('APPLEMANIAK_SONDAGE_PATH') )            define('APPLEMANIAK_SONDAGE_PATH',         plugin_dir_path( __FILE__ ));
if( !defined('APPLEMANIAK_SONDAGE_URL') )             define('APPLEMANIAK_SONDAGE_URL',          plugins_url('applemaniak-sondage-widget/'));
if( !defined('APPLEMANIAK_SONDAGE_URL_WP') )          define('APPLEMANIAK_SONDAGE_URL_WP',       get_bloginfo('url'));
if( !defined('APPLEMANIAK_SONDAGE_URL_WP_ADMIN') )    define('APPLEMANIAK_SONDAGE_URL_WP_ADMIN', APPLEMANIAK_SONDAGE_URL_WP.'/wp-admin/');
if( !defined('APPLEMANIAK_SONDAGE_TEXTDOMAIN') )      define('APPLEMANIAK_SONDAGE_TEXTDOMAIN',   'applemaniak-sondage');
if( !defined('APPLEMANIAK_SONDAGE_VIEWS_FOLDER') )    define('APPLEMANIAK_SONDAGE_VIEWS_FOLDER', APPLEMANIAK_SONDAGE_PATH.'views/');
if( !defined('APPLEMANIAK_SONDAGE_ICON_URL') )        define('APPLEMANIAK_SONDAGE_ICON_URL', APPLEMANIAK_SONDAGE_URL.'res/img/poll.png');
if( !defined('APPLEMANIAK_SONDAGE_LOADER_URL') )        define('APPLEMANIAK_SONDAGE_LOADER_URL', APPLEMANIAK_SONDAGE_URL.'res/img/loader.gif');
if( !defined('APPLEMANIAK_SONDAGE_RESOURCES_URL') )   define('APPLEMANIAK_SONDAGE_RESOURCES_URL', APPLEMANIAK_SONDAGE_URL.'res/');
if( !defined('APPLEMANIAK_SONDAGE_RESOURCES_FOLDER') )define('APPLEMANIAK_SONDAGE_RESOURCES_FOLDER', APPLEMANIAK_SONDAGE_PATH.'res/');
if( !defined('APPLEMANIAK_SONDAGE_LIBS_FOLDER') )     define('APPLEMANIAK_SONDAGE_LIBS_FOLDER', APPLEMANIAK_SONDAGE_PATH.'lib/');

// Conditional check for SSL enabled site
if(!defined('APPLEMANIAK_SONDAGE_URL_WP_AJAX'))
{
    if ( is_ssl() )
    {
        define('APPLEMANIAK_SONDAGE_URL_WP_AJAX', admin_url('admin-ajax.php', 'https'));
    }
    else
    {
        define('APPLEMANIAK_SONDAGE_URL_WP_AJAX', admin_url('admin-ajax.php', 'http'));
    }
}

/**
 * Tables names into database
 */
if( !defined('APPLEMANIAK_SONDAGE_TABLE_POLL') )   define('APPLEMANIAK_SONDAGE_TABLE_POLL', 'applemaniak_polls');
if( !defined('APPLEMANIAK_SONDAGE_TABLE_ANSWER') ) define('APPLEMANIAK_SONDAGE_TABLE_ANSWER', 'applemaniak_polls_answers');
if( !defined('APPLEMANIAK_SONDAGE_TABLE_VOTE') )   define('APPLEMANIAK_SONDAGE_TABLE_VOTE', 'applemaniak_polls_votes');


/** Localization **/
function applemaniak_sondage_text_domain_init()
{
    load_plugin_textdomain(APPLEMANIAK_SONDAGE_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/lang');
}
add_action('init', 'applemaniak_sondage_text_domain_init');


/** Include Required Plugin Files **/
require_once APPLEMANIAK_SONDAGE_PATH.'object/Applemaniak_Sondage.php';

/** Initialize the plugin's base class **/
$Applemaniak_Sondage = new Applemaniak_Sondage();

/** Activation Hooks **/
register_activation_hook( __FILE__ ,		array( &$Applemaniak_Sondage, 'activate') );
register_deactivation_hook( __FILE__ ,	array( &$Applemaniak_Sondage, 'desactivate') );