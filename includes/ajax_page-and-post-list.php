<?php
/*
 * WPGear. Page and Post Description
 * ajax_page-and-post-list.php
 */	 
 
	$current_user = wp_get_current_user();	
	$User_Name 	= $current_user->user_login;
		
	$Mode 			= isset($_REQUEST['mode']) ? sanitize_text_field($_REQUEST['mode']) : null;
	$Items_ID		= isset($_REQUEST['items_id']) ? sanitize_text_field($_REQUEST['items_id']) : null;
	$Item_ID		= isset($_REQUEST['item_id']) ? sanitize_text_field($_REQUEST['item_id']) : null;
	$Type 			= isset($_REQUEST['type']) ? sanitize_text_field($_REQUEST['type']) : null;
	$Item_Content 	= isset($_REQUEST['note']) ? sanitize_text_field($_REQUEST['note']) : '';	

	global $PageAndPostDescription_plugin_url;	

	$Notes = array();
	
	$Result = false; 
	
	// get_description
	if ($Mode == 'get_description') {		
		if ($Items_ID) {
			global $wpdb;
			$PageAndPostDescription_PostMeta_table = $wpdb->prefix .'postmeta';				
			
			$Items_ID_Array = explode(',', $Items_ID);
			
			// Prepare Query
			$Count_Items = count($Items_ID_Array);			
			$ListID = array_fill(0, $Count_Items, '%d');
			$ListID = implode( ', ', $ListID);			
				
			if ($Type == 'page' || $Type == 'post') {
				//Pages	& Posts			
				$Query = "SELECT post_id as ID, meta_value as Description FROM $PageAndPostDescription_PostMeta_table WHERE post_id IN ($ListID) AND meta_key = 'pnpd_description'";					
				$Records = $wpdb->get_results ($wpdb->prepare ($Query, $Items_ID_Array));					

				foreach ($Items_ID_Array as $ID) {
					$Description = '';
					
					foreach ($Records as $Record) {
						$Record_ID 			= $Record->ID;
						$Record_Description = $Record->Description;
						
						if ($ID == $Record_ID) {
							if ($Record_Description) {
								$Description = $Record_Description;
							}
						}					
					}					
					
					$Meta = array (
						'id' => $ID,
						'note' => $Description,
					);
					
					$Notes[] = $Meta;
				}
			}
		}
		
		$Result = true;	
	}
	
	// Save
	if ($Mode == 'save_description') {
		if ($Item_ID) {
			update_post_meta ($Item_ID, 'pnpd_description', $Item_Content);
			
			$Result = true;
		}
	}
	
	$Obj_Request = new stdClass();
	$Obj_Request->status 	= 'OK';
	$Obj_Request->answer 	= $Result;
	$Obj_Request->notes 	= $Notes;

	wp_send_json($Obj_Request);    

	die; // Complete.