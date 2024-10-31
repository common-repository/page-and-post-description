<?php
/*
 * WPGear. Page and Post Description
 * uninstall.php
 */	

	if ( !defined( 'ABSPATH' ) && !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
		exit();
	}

	global $wpdb;
	
	$PageAndPostDescription_PostMeta_table = $wpdb->prefix .'postmeta';	
	
	// Удаляем MetaFields Плагина
	$Query = "DELETE FROM $PageAndPostDescription_PostMeta_table WHERE meta_key = 'pnpd_description'";		
	$wpdb->query($Query);	