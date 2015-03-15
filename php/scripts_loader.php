<?php

add_action('init', 'fn_adt_register_scripts');
function fn_adt_register_scripts() 
{
	fn_adt_register_styles();	
	fn_adt_register_javascripts();
}

add_action('wp_footer', 'fn_print_adt_scripts');
function fn_print_adt_scripts() 
{
	//Reference "The Jedi Knight way" section - http://scribu.net/wordpress/optimal-script-loading.html
	global $add_demo_adt_scripts;

	fn_adt_register_jqueryui_css($theme='');

	if ($add_demo_adt_scripts)
	{
		//optional		
		
		wp_enqueue_style('jqueryui_css');
		
		//Datatable css and js
		wp_enqueue_style('datatable_css');
		wp_enqueue_script('datatable_js');
		
		//demo datatable
		wp_enqueue_script('demo_datatable_js');
		wp_localize_script('demo_datatable_js', 'AdoreDTAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}
}

/**
 * Javascript for Adore Datatable Admin Control Panel 
 */
add_action('admin_enqueue_scripts', 'fn_adt_admin_load_scripts');
function fn_adt_admin_load_scripts($hook) 
{ 
	global $adt_settings_page;	
 
	if( $hook != $adt_settings_page ) //ref. https://pippinsplugins.com/loading-scripts-correctly-in-the-wordpress-admin/
	{
		return;		
	}	
 	wp_enqueue_script('datatables_admin_js');
}


/**
 * Function to register CSS Files
 */
function fn_adt_register_styles()
{
	$static_version='1.1'; //used for css which will never be modified	
	$dynamic_version='1.2'; //used for css which will be modified
	
	
	$adt_css_version=get_option('adt_css_version');
	if($adt_css_version==FALSE)
	{
		$adt_css_version=1;
		$deprecated = null;
	    $autoload = 'no';
	    add_option( 'adt_css_version', $adt_css_version, $deprecated, $autoload );			
	}
	
	//Web Assets	
	wp_register_style('datatable_css', 'http://cdn.datatables.net/1.10.5/css/jquery.dataTables.css',array(),$static_version,'all');
	wp_register_style('bootstrap_datatable_css', 'http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.css',array(),$static_version,'all');
	wp_register_style('jqueryui_datatable_css', 'http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/jqueryui/dataTables.jqueryui.css',array(),$static_version,'all');
	
	//bootstrap css
	wp_register_style('bootstrap_css', 'http://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',array(),$static_version,'all');
	//custom adore datatable css
	wp_register_style('adt_custom_css', plugins_url('/assets/css/adt_custom_css.css', dirname(__FILE__) ),array(),$adt_css_version,'all');
	
	
}

/**
 * Function to register JS
 */
function fn_adt_register_javascripts()
{
	//scripts registration here
	$static_version='1.1'; //used for js which will never be modified	
	$dynamic_version='1.3'; //used for js which will be modified	
	
	$adt_js_version=get_option('adt_js_version');
	if($adt_js_version==FALSE)
	{
		$adt_js_version=1;
		$deprecated = null;
	    $autoload = 'no';
	    add_option( 'adt_js_version', $adt_js_version, $deprecated, $autoload );			
	}	
	
	//Web Assets
	wp_register_script('datatable_js', 'http://cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js', array(jquery), $static_version,TRUE);
	wp_register_script('bootstrap_datatable_js', 'http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/bootstrap/3/dataTables.bootstrap.js', array(jquery), $static_version,TRUE);	
	wp_register_script('jqueryui_datatable_js', 'http://cdn.datatables.net/plug-ins/f2c75b7247b/integration/jqueryui/dataTables.jqueryui.js', array(jquery), $static_version,TRUE);
	
	
	//Javascript for Demo Adore Datatable
	wp_register_script('demo_datatable_js', plugins_url('/assets/js/demo_datatables.js', dirname(__FILE__) ), array(jquery), $dynamic_version,TRUE); //Ref. http://codex.wordpress.org/Function_Reference/plugins_url	
	
	//Adore Datatable Custom Javascript Code
	wp_register_script('adt_custom_js', plugins_url('/assets/js/adt_custom_js.js', dirname(__FILE__) ), array(jquery), $adt_js_version,TRUE); //Ref. http://codex.wordpress.org/Function_Reference/plugins_url
	
	//Javascript for Adore Datatable Admin Control Panel
	wp_register_script('datatables_admin_js', plugins_url('/assets/js/adore-datatables-admin.js', dirname(__FILE__) ), array(jquery), $dynamic_version,TRUE); //Ref. http://codex.wordpress.org/Function_Reference/plugins_url	
}

function fn_adt_register_jqueryui_css($theme='')
{
	$jqueryui_version='1.11.4'; //jQueryUI Css will be based on this version. Can be changed manually.	
	$jquery_ui_theme='smoothness';	//this value will be fetched from the database
	if(!empty($theme))
	{
		$jquery_ui_theme=$theme;
	}
	
	//register various jqueryui themes css	
	$cdn_url='https://code.jquery.com/ui/'.$jqueryui_version.'/themes/'.$jquery_ui_theme.'/jquery-ui.css'; //default theme
	wp_register_style('jqueryui_css', $cdn_url, array(), '1.1', 'all');
}
