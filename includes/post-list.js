// WPGear. Page and Post Description
// post-list.js
	
	window.addEventListener ('load', function() {
		console.log('post-list.js - is Loaded.');
		
		var PnPD_Row;
		var PnPD_Item_ID;
		
		var PnPD_Pages = new Array ();
		var PnPD_Table_Items = document.getElementById("the-list");		
		
		if (PnPD_Table_Items) {
			// Message Box
			var PnPD_Table_Message_Box = document.createElement("div");
			PnPD_Table_Message_Box.id = 'post-list_messagebox';
			PnPD_Table_Message_Box.className = 'post-list_messagebox';
			PnPD_Table_Message_Box.innerHTML = '... get Notes processing ...';
			PnPD_Table_Items.insertAdjacentElement("beforeBegin", PnPD_Table_Message_Box)
			
			for (i = 0; i < PnPD_Table_Items.children.length; i++) {
				PnPD_Row = PnPD_Table_Items.children[i];
				PnPD_Item_ID = PnPD_Row.querySelectorAll('[id^=cb-select-]')[0].value;		
				
				PnPD_Pages.push(PnPD_Item_ID);
			}

			var PnPD_Ajax_URL 	= ajaxurl;
			var PnPD_Ajax_Data 	= 'action=page_and_post_description&mode=get_description&items_id=' + PnPD_Pages + '&type=post';
			
			jQuery.ajax({
				type:"POST",
				url: PnPD_Ajax_URL,
				dataType: 'json',
				data: PnPD_Ajax_Data,
				cache: false,
				success: function(jsondata) {
					var Obj_Request = jsondata;	
					
					var Status	= Obj_Request.status;
					var Answer 	= Obj_Request.answer;					
					PnPD_Notes  = Obj_Request.notes;
				
					if (PnPD_Notes) {
						PnPD_Count_Columns = PnPD_Table_Items.rows[0].cells.length
				
						for (i = 0; i < PnPD_Notes.length; i++) {
							PnPD_Item_ID 		= PnPD_Notes[i].id;
							PnPD_Note_Content 	= PnPD_Notes[i].note;
							
							PnPD_Row = document.getElementById("post-" + PnPD_Item_ID);
							
							var PnPD_Note_Box = document.createElement("tr");
							PnPD_Note_Box.id = 'post-list_note-box_' + PnPD_Item_ID;
							
							PnPD_Row.insertAdjacentElement("afterend", PnPD_Note_Box);

							var PnPD_Note_Label = "<span id='pnpd_note_control_" + PnPD_Item_ID + "' class='post-list_note-box-label' title='Click to Edit' onclick='PnPD_note_edit(" + PnPD_Item_ID + ")';>Note: </span>";
							PnPD_Note_Box_Content = '';
							
							PnPD_Note_Box_Content +=	'<td colspan ="' + PnPD_Count_Columns + '" class="post-list_note-box">';
							PnPD_Note_Box_Content +=	'<span>';
							PnPD_Note_Box_Content +=	PnPD_Note_Label;
							PnPD_Note_Box_Content +=	'</span>';
							PnPD_Note_Box_Content +=	'<span id="pnpd_note_content_' + PnPD_Item_ID + '">';
							PnPD_Note_Box_Content +=	PnPD_Note_Content;
							PnPD_Note_Box_Content +=	'</span>';
							PnPD_Note_Box_Content +=	'</td>';
											
							PnPD_Note_Box.innerHTML = PnPD_Note_Box_Content;								
						}
						PnPD_Table_Message_Box.style.display = 'none';
					}
				}
			});				
		}			
	});
	
	function PnPD_note_edit (Item_ID) {
		PnPD_Note_Box_Content = document.getElementById ('post-list_note-box_' + Item_ID).innerHTML;
		
		var Note_Box;
		var Note_ID = null;		

		for (i = 0; i < PnPD_Notes.length; i++) {		
			if (PnPD_Notes[i].id == Item_ID) {
				PnPD_Note_Content = PnPD_Notes[i].note;	
				Note_ID = i
			}
		}
	
		Note_Box = '<td colspan=' + PnPD_Count_Columns + '>';
		Note_Box += '<div class="post-list_note-box-edit"><textarea id="post-list_note-box-edit_content" class="post-list_note-box-edit-content">' + PnPD_Note_Content + '</textarea>';
		Note_Box += '<input id="post-list_note-box-edit_btn_save" type="button" class="button button-primary" value="Save Note">';
		Note_Box += '<input id="post-list_note-box-edit_btn_cancel" type="button" class="button" style="margin-left: 10px;" value="Cancel"></div>';
		Note_Box += '</td>';
		
		document.getElementById ("post-list_note-box_" + Item_ID).innerHTML = Note_Box;

		var PnPD_Notes_Btn_Save 	= document.getElementById ("post-list_note-box-edit_btn_save");
		var PnPD_Notes_Btn_Cancel 	= document.getElementById ("post-list_note-box-edit_btn_cancel");

		// Save		
		PnPD_Notes_Btn_Save.addEventListener ("click", function(e) {
			PnPD_Note_Content = document.getElementById ('post-list_note-box-edit_content').value;
			
			document.getElementById ('post-list_note-box-edit_content').innerHTML = PnPD_Note_Content;
			document.getElementById ('post-list_note-box-edit_content').style.color = 'darkgrey';
			
			var PnPD_Ajax_URL = ajaxurl;
			var PnPD_Ajax_Data = 'action=page_and_post_description&mode=save_description&item_id=' + Item_ID + '&note=' + PnPD_Note_Content;
			
			//Save Note	
			jQuery.ajax({
				type:"POST",
				url: PnPD_Ajax_URL,
				dataType: 'json',
				data: PnPD_Ajax_Data,
				cache: false,
				success: function(jsondata) {
					var Obj_Request = jsondata;	
					
					var Status	= Obj_Request.status;
					var Answer 	= Obj_Request.answer;					

					console.log('Note saved.');
					
					document.getElementById ('post-list_note-box_' + Item_ID).innerHTML = PnPD_Note_Box_Content;
					document.getElementById ('pnpd_note_content_' + Item_ID).innerHTML = PnPD_Note_Content;
					
					PnPD_Notes[Note_ID] = PnPD_Note_Content;
				}
			});	
			
		
		}, false);	
		
		// Cancel
		PnPD_Notes_Btn_Cancel.addEventListener ("click", function(e) {
			document.getElementById ('post-list_note-box_' + Item_ID).innerHTML = PnPD_Note_Box_Content;
		}, false);			
	}		