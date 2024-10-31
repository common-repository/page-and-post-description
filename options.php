<?php
/*
 * WPGear. Page and Post Description
 * options.php
 */

	$PageAndPostDescription_Action = isset($_REQUEST['action']) ? sanitize_text_field ($_REQUEST['action']) : null; 

	if ($PageAndPostDescription_Action == 'update') {
		// Save Options.
		$PageAndPostDescription_Setup_AdminOnly 	= (isset($_REQUEST['pnpd_option_adminonly'])) ? 1 : 0;
		$PageAndPostDescription_Setup_ShowAuthor 	= (isset($_REQUEST['pnpd_option_show_author'])) ? 1 : 0;
		$PageAndPostDescription_Setup_ShowDate 		= (isset($_REQUEST['pnpd_option_show_date'])) ? 1 : 0;
		$PageAndPostDescription_Setup_PageEnable 	= (isset($_REQUEST['pnpd_option_page_enable'])) ? 1 : 0;
		$PostAndPostDescription_Setup_PostEnable 	= (isset($_REQUEST['pnpd_option_post_enable'])) ? 1 : 0;
		
		$PageAndPostDescription_Options = get_option("page-and-post-description_options", array());

		$PageAndPostDescription_Options = array(
			'adminonly' => $PageAndPostDescription_Setup_AdminOnly,
			'show_author' => $PageAndPostDescription_Setup_ShowAuthor,
			'show_date' => $PageAndPostDescription_Setup_ShowDate,
			'page_enable' => $PageAndPostDescription_Setup_PageEnable,
			'post_enable' => $PostAndPostDescription_Setup_PostEnable		
		);	
		
		update_option('page-and-post-description_options', $PageAndPostDescription_Options);
	}

	$PageAndPostDescription_Options = get_option("page-and-post-description_options", array(
		'adminonly' => 1,
		'show_author' => 0,
		'show_date' => 0,
		'page_enable' => 1,
		'post_enable' => 1
		)
	);

	$PageAndPostDescription_Setup_AdminOnly = (isset($PageAndPostDescription_Options['adminonly'])) ? intval($PageAndPostDescription_Options['adminonly']) : 1;	
	
	if ($PageAndPostDescription_Setup_AdminOnly) {
		if (!current_user_can('edit_dashboard')) {
			?>
			<div class="pageandpostdescription_options_warning" style="margin: 40px;">
				Sorry, you are not allowed to view this page.
			</div>
			<?php
			
			return;
		}		
	}	

	?>
	<style>
		.pnpd_about_pro {
			background: aliceblue;
			padding: 5px;
			border-radius: 9px;
		}
		.pnpd_about_pro ul {
			list-style: initial;
		}
		.pnpd_about_pro li {
			margin-top: 5px;
			margin-left: 40px;
		}
	</style>
	
	<div class="wrap">
		<h2>Page and Post Description.</h2>
		<hr>
		
		<div id="pnpd_option_box" style="margin-left: 20px;">			
			<form name="form_PageAndPostDescription_Options" method="post" style="margin-top: 20px;">
				<div style="margin-top: 10px;">
					<label for="pnpd_option_adminonly" title="On/Off">
						Enable this Page for Admin only
					</label>
					<input id="pnpd_option_adminonly" name="pnpd_option_adminonly" type="checkbox" <?php if($PageAndPostDescription_Setup_AdminOnly) {echo 'checked';} ?>>
				</div>	

				<div style="margin-top: 20px; margin-left: 42px; color: grey;">
					<label for="pnpd_option_show_author" title="On/Off">
						Show Description Author
					</label>
					<input id="pnpd_option_show_author" name="pnpd_option_show_author" type="checkbox" disabled>
				</div>		

				<div style="margin-top: 10px; margin-left: 54px; color: grey;">
					<label for="pnpd_option_show_date" title="On/Off">
						Show Description Date
					</label>
					<input id="pnpd_option_show_date" name="pnpd_option_show_date" type="checkbox" disabled>
				</div>	

				<div style="margin-top: 20px; margin-left: 72px;">
					<label for="pnpd_option_page_enable" title="On/Off">
						Enable on Page List
					</label>
					<input id="pnpd_option_page_enable" name="pnpd_option_page_enable" type="checkbox" <?php if($PageAndPostDescription_Options['page_enable']) {echo 'checked';} ?>>
				</div>		

				<div style="margin-top: 10px; margin-left: 76px;">
					<label for="pnpd_option_post_enable" title="On/Off">
						Enable on Post List
					</label>
					<input id="pnpd_option_post_enable" name="pnpd_option_post_enable" type="checkbox" <?php if($PageAndPostDescription_Options['post_enable']) {echo 'checked';} ?>>
				</div>					
				
				<div style="margin-top: 10px; margin-bottom: 5px; text-align: right;">
					<input id="pnpd_btn_options_save" type="submit" class="button button-primary" style="margin-right: 5px;" value="Save">
				</div>
				<input id="action" name="action" type="hidden" value="update">			
			</form>
			
			<hr>
			<div class="pnpd_about_pro">
				<ul>
					Check out the features of the PRO version: <a href="http://wpgear.xyz/page-and-post-description-pro/">"Page and Post Description PRO:"</a>
					<li>Works for any Castoms Post-Types.</li>
					<li>Automatic finding of all Castoms Post-Types and their connection.</li>
				</ul>				
			</div>
		</div>			
	</div>
