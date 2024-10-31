<?php
/*
Plugin Name: Page and Post Description
Plugin URI: wpgear.xyz/page-and-post-description/
Description: Description for each Page and Post. Check out the features of the PRO version: <a href="http://wpgear.xyz/page-and-post-description-pro/">"Page and Post Description PRO"</a>.
Version: 1.5
Author: WPGear
Author URI: http://wpgear.xyz
License: GPLv2
*/

	$PageAndPostDescription_plugin_url = plugin_dir_url( __FILE__); // со слэшем на конце	
	
	$PageAndPostDescription_Options = get_option("page-and-post-description_options", array(
		'adminonly' => 1,
		'show_author' => 0,
		'show_date' => 0,
		'page_enable' => 1,
		'post_enable' => 1
		)
	);	
	
	/* Admin Console - Styles.
	----------------------------------------------------------------- */	
	function PageAndPostDescription_admin_style ($hook) {
		global $PageAndPostDescription_plugin_url;
		
		$screen = get_current_screen();
		$screen_base = $screen->base;			
		
		if (PageAndPostDescription_is_Page_ListPost ($screen_base) || PageAndPostDescription_is_Post_ListPost ($screen_base)) {
			wp_enqueue_style ('page-and-post-list', $PageAndPostDescription_plugin_url .'admin-style.css');
			wp_enqueue_script ('page-list', $PageAndPostDescription_plugin_url .'includes/post-list.js');
		}		
	}
	add_action ('admin_enqueue_scripts', 'PageAndPostDescription_admin_style' );
	
	/* Check is Page-List
	----------------------------------------------------------------- */	
	function PageAndPostDescription_is_Page_ListPost ($screen_base) {
		$Result = false;

		global $PageAndPostDescription_Options;

		$PageAndPostDescription_Enable = (isset($PageAndPostDescription_Options['page_enable'])) ? intval($PageAndPostDescription_Options['page_enable']) : 1;	
		
		if ($PageAndPostDescription_Enable) {
			if ($screen_base == 'edit') {
				if (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'page') {
					$Result = true;
				}
			}	
		}
		
		return $Result;
	}

	/* Check is Post-List
	----------------------------------------------------------------- */	
	function PageAndPostDescription_is_Post_ListPost ($screen_base) {
		$Result = false;

		global $PageAndPostDescription_Options;

		$PageAndPostDescription_Enable = (isset($PageAndPostDescription_Options['post_enable'])) ? intval($PageAndPostDescription_Options['post_enable']) : 1;	
		
		if ($PageAndPostDescription_Enable) {		
			if ($screen_base == 'edit') {
				if (empty($_REQUEST)) {
					$Result = true;
				}
			}	
		}
		
		return $Result;
	}	

	/* Add Field 'Description' to Page and Post Metabox
	----------------------------------------------------------------- */
	function PageAndPostDescription_Add_MetaField() {
		
global $PageAndPostDescription_Options;

$PageAndPostDescription_Page_Enable = (isset($PageAndPostDescription_Options['page_enable'])) ? intval($PageAndPostDescription_Options['page_enable']) : 1;	
$PageAndPostDescription_Post_Enable = (isset($PageAndPostDescription_Options['post_enable'])) ? intval($PageAndPostDescription_Options['post_enable']) : 1;	
		
$Screens = array ();

if ($PageAndPostDescription_Page_Enable) {
	array_push ($Screens, "page");
}

if ($PageAndPostDescription_Post_Enable) {
	array_push ($Screens, "post");
}
		
		add_meta_box( 
			'PageAndPostDescription_MetaField', 
			__('Description', 'descriptions'), 
			'PageAndPostDescription_MetaField_Callback', 
			$Screens, 
			'advanced', 
			'high', 
			null 
		);
	}
	add_action( 'add_meta_boxes', 'PageAndPostDescription_Add_MetaField' );	

	/* Metabox Description.
	----------------------------------------------------------------- */	
	function PageAndPostDescription_MetaField_Callback ($Post, $Meta) {
		$PageAndPostDescription_Note = "";
		
		$Post_ID = $Post->ID;
		
		if ($Post_ID) {
			$PageAndPostDescription_Note = get_post_meta ($Post_ID, 'pnpd_description', true );
		}		
		
		$Metabox_Description = "";
		
		$Metabox_Description .= "<div>";
		$Metabox_Description .= "<textarea name='pnpd_description' rows='2' style='width: 100%;'>$PageAndPostDescription_Note</textarea>";
		$Metabox_Description .= "</div>";	
		
		echo $Metabox_Description;
	}

	/* Save Metabox Description.
	----------------------------------------------------------------- */
	function PageAndPostDescription_Save_MetaField ($Post_ID) {
		$PageAndPostDescription_Note = isset($_REQUEST['pnpd_description']) ? sanitize_text_field ($_REQUEST['pnpd_description']) : null;	// Not - sanitize_textarea_field. В Описаниях не должно быть переносов строк. Иначе, будет плохо выглядеть в Списках.
		
		if ($PageAndPostDescription_Note) {
			update_post_meta( $Post_ID, 'pnpd_description', $PageAndPostDescription_Note );
		}
	}
	add_action( 'save_post', 'PageAndPostDescription_Save_MetaField');	
	
	/* Create plugin SubMenu
	----------------------------------------------------------------- */		
	function PageAndPostDescription_create_menu() {
		add_options_page(
			'Page and Post Description',
			'Page and Post Description',
			'publish_posts',
			'page-and-post-description/options.php',
			''
		);	
	}
	add_action('admin_menu', 'PageAndPostDescription_create_menu');	
	
	/* AJAX Processing
	----------------------------------------------------------------- */    
    function PageAndPostDescription_Ajax(){		
		include_once ('includes/ajax_page-and-post-list.php');
    }
	add_action( 'wp_ajax_page_and_post_description', 'PageAndPostDescription_Ajax' );