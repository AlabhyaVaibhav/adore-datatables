<?php
/*
Plugin Name: Adore Datatables
Plugin URI: https://github.com/adoreits/adore-datatables
Description: Adore Datatables (Wordpress and Datatables Integration Project). Use the shortcode [adt_demo_datatables] to load the demo datatable.
Version: 0.0.2
Author: Niraj Kumar (Adore ITS)
Author URI: http://www.adoreits.com/
*/

define("ADT_DEMO_TABLE_NAME", "adt_demo_table");
define("PLUGIN_ADMIN_PAGE_SLUG", "adore-datatables");
include_once('php/scripts_loader.php');
include_once('php/demo_datatables.php');
include_once('php/admin-settings.php');
include_once('php/adore-datatables.php');

//$adt_plugin_dir_path=;

define("ADT_PLUGIN_DIR_PATH", plugin_dir_path( __FILE__ ));

/**
 * Create Demo table and fill it with data on plugin activation
 * This may generate error/notice in some installation. Need to investigate the issue.
 * No harm done though.
 * 
 * Need to implement uninstall hook to cleanup the tables created by this plugin.
 */
register_activation_hook( __FILE__, 'fn_create_adt_table' );
register_activation_hook( __FILE__, 'fn_install_adt_data' );

function fn_create_adt_table() 
{
	global $wpdb;
	
	$table_prefix=$wpdb->prefix;

	$table_name = $table_prefix.ADT_DEMO_TABLE_NAME;
	
	//query to delete any existing table;
	$wpdb->query("DROP TABLE IF EXISTS $table_name");	
	
	$charset_collate = $wpdb->get_charset_collate();
	
	//create table with dbDelta
	/*Example reference http://codex.wordpress.org/Creating_Tables_with_Plugins
	 * $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		name tinytext NOT NULL,
		text text NOT NULL,
		url varchar(55) DEFAULT '' NOT NULL,
		UNIQUE KEY id (id)
	) $charset_collate;";*/
	
	$sql="CREATE TABLE $table_name (
	  id int(10) NOT NULL AUTO_INCREMENT,
	  engine varchar(255) NOT NULL default '',
	  browser varchar(255) NOT NULL default '',
	  platform varchar(255) NOT NULL default '',
	  version float,
	  grade varchar(20) NOT NULL default '',
	  PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	
	$str_sql="
		CREATE TABLE IF NOT EXISTS adore_datatable_settings (
			adt_id int(11) NOT NULL AUTO_INCREMENT,
			adt_table_name text NOT NULL,
			adt_table_slug text NOT NULL,
			adt_table_settings text NOT NULL
			PRIMARY KEY  (adt_id)
		) $charset_collate;
	";
	dbDelta($str_sql);
}

function fn_install_adt_data() {
	global $wpdb;	
	$table_name = $wpdb->prefix.ADT_DEMO_TABLE_NAME;
	$str_query="
		INSERT
			INTO $table_name  
				( engine, browser, platform, version, grade ) 
			VALUES 
				( 'Trident', 'Internet Explorer 4.0', 'Win 95+', '4', 'X' ),		
				( 'Trident', 'Internet Explorer 5.0', 'Win 95+', '5', 'C' ),		    
				( 'Trident', 'Internet Explorer 5.5', 'Win 95+', '5.5', 'A' ),
				( 'Trident', 'Internet Explorer 6', 'Win 98+', '6', 'A' ),
				( 'Trident', 'Internet Explorer 7', 'Win XP SP2+', '7', 'A' ),
				( 'Trident', 'AOL browser (AOL desktop)', 'Win XP', '6', 'A' ),
				( 'Gecko', 'Firefox 1.0', 'Win 98+ / OSX.2+', '1.7', 'A' ),
				( 'Gecko', 'Firefox 1.5', 'Win 98+ / OSX.2+', '1.8', 'A' ),
				( 'Gecko', 'Firefox 2.0', 'Win 98+ / OSX.2+', '1.8', 'A' ),
				( 'Gecko', 'Firefox 3.0', 'Win 2k+ / OSX.3+', '1.9', 'A' ),
				( 'Gecko', 'Camino 1.0', 'OSX.2+', '1.8', 'A' ),
				( 'Gecko', 'Camino 1.5', 'OSX.3+', '1.8', 'A' ),
				( 'Gecko', 'Netscape 7.2', 'Win 95+ / Mac OS 8.6-9.2', '1.7', 'A' ),
				( 'Gecko', 'Netscape Browser 8', 'Win 98SE+', '1.7', 'A' ),
				( 'Gecko', 'Netscape Navigator 9', 'Win 98+ / OSX.2+', '1.8', 'A' ),
				( 'Gecko', 'Mozilla 1.0', 'Win 95+ / OSX.1+', '1', 'A' ),
				( 'Gecko', 'Mozilla 1.1', 'Win 95+ / OSX.1+', '1.1', 'A' ),
				( 'Gecko', 'Mozilla 1.2', 'Win 95+ / OSX.1+', '1.2', 'A' ),
				( 'Gecko', 'Mozilla 1.3', 'Win 95+ / OSX.1+', '1.3', 'A' ),
				( 'Gecko', 'Mozilla 1.4', 'Win 95+ / OSX.1+', '1.4', 'A' ),
				( 'Gecko', 'Mozilla 1.5', 'Win 95+ / OSX.1+', '1.5', 'A' ),
				( 'Gecko', 'Mozilla 1.6', 'Win 95+ / OSX.1+', '1.6', 'A' ),
				( 'Gecko', 'Mozilla 1.7', 'Win 98+ / OSX.1+', '1.7', 'A' ),
				( 'Gecko', 'Mozilla 1.8', 'Win 98+ / OSX.1+', '1.8', 'A' ),
				( 'Gecko', 'Seamonkey 1.1', 'Win 98+ / OSX.2+', '1.8', 'A' ),
				( 'Gecko', 'Epiphany 2.20', 'Gnome', '1.8', 'A' ),
				( 'Webkit', 'Safari 1.2', 'OSX.3', '125.5', 'A' ),
				( 'Webkit', 'Safari 1.3', 'OSX.3', '312.8', 'A' ),
				( 'Webkit', 'Safari 2.0', 'OSX.4+', '419.3', 'A' ),
				( 'Webkit', 'Safari 3.0', 'OSX.4+', '522.1', 'A' ),
				( 'Webkit', 'OmniWeb 5.5', 'OSX.4+', '420', 'A' ),
				( 'Webkit', 'iPod Touch / iPhone', 'iPod', '420.1', 'A' ),
				( 'Webkit', 'S60', 'S60', '413', 'A' ),
				( 'Presto', 'Opera 7.0', 'Win 95+ / OSX.1+', NULL, 'A' ),
				( 'Presto', 'Opera 7.5', 'Win 95+ / OSX.2+', NULL, 'A' ),
				( 'Presto', 'Opera 8.0', 'Win 95+ / OSX.2+', NULL, 'A' ),
				( 'Presto', 'Opera 8.5', 'Win 95+ / OSX.2+', NULL, 'A' ),
				( 'Presto', 'Opera 9.0', 'Win 95+ / OSX.3+', NULL, 'A' ),
				( 'Presto', 'Opera 9.2', 'Win 88+ / OSX.3+', NULL, 'A' ),
				( 'Presto', 'Opera 9.5', 'Win 88+ / OSX.3+', NULL, 'A' ),
				( 'Presto', 'Opera for Wii', 'Wii', NULL, 'A' ),
				( 'Presto', 'Nokia N800', 'N800', NULL, 'A' ),
				( 'Presto', 'Nintendo DS browser', 'Nintendo DS', '8.5', 'C' ),
				( 'KHTML', 'Konqureror 3.1', 'KDE 3.1', '3.1', 'C' ),
				( 'KHTML', 'Konqureror 3.3', 'KDE 3.3', '3.3', 'A' ),
				( 'KHTML', 'Konqureror 3.5', 'KDE 3.5', '3.5', 'A' ),
				( 'Tasman', 'Internet Explorer 4.5', 'Mac OS 8-9', NULL, 'X' ),
				( 'Tasman', 'Internet Explorer 5.1', 'Mac OS 7.6-9', '1', 'C' ),
				( 'Tasman', 'Internet Explorer 5.2', 'Mac OS 8-X', '1', 'C' ),
				( 'Misc', 'NetFront 3.1', 'Embedded devices', NULL, 'C' ),
				( 'Misc', 'NetFront 3.4', 'Embedded devices', NULL, 'A' ),
				( 'Misc', 'Dillo 0.8', 'Embedded devices', NULL, 'X' ),
				( 'Misc', 'Links', 'Text only', NULL, 'X' ),
				( 'Misc', 'Lynx', 'Text only', NULL, 'X' ),
				( 'Misc', 'IE Mobile', 'Windows Mobile 6', NULL, 'C' ),
				( 'Misc', 'PSP browser', 'PSP', NULL, 'C' ),
				( 'Other browsers', 'All others', '', NULL, 'U' )
	";
	$wpdb->query($str_query);
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fn_adt_add_settings_link' );
function fn_adt_add_settings_link ($links) 
{
	//Ref. http://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	$links[] = '<a href="'.admin_url('admin.php?page='.PLUGIN_ADMIN_PAGE_SLUG).'">Settings</a>';
	return $links;
}

if(!function_exists('fn_applog'))
{
	function fn_applog($str_val)
	{
		if(empty($str_val))
		{
			$str_val='\n Empty call';
		}
		$str_val= "\n".$str_val;
		$my_file = plugin_dir_path(__FILE__).'applog.txt';
		$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
		fwrite($handle, $str_val);
		fclose($handle);
	}
}