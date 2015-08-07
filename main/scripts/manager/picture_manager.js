
function listFile( element ){
	var parent_li = element.nearestParent( "li" ),
	add_folder_img = parent_li.lastChildOfType( 'img' ),
	src = constants.base_url+"/style/resources/";
	
	if( element.hasAttribute( 'data-filepath' ) && !element.hasAttribute( 'data-loaded' ) ){
		var path = element.getAttribute( 'data-filepath' );
		
		controller.getText( constants.ajax_url+'?action=0&dir_path='+path, function(d){
			if( d.length > 0 ){
				var list = createElement("ul", {
					"class":"folders",
					"innerHTML":d
				})
				parent_li.appendChild( list );
				element.setAttribute( 'data-loaded', "" );
				element.src = src+= "folder-open.png"; 
				add_folder_img.removeClass("hide");
			}
		})
		
	}else{
		var ul = parent_li.getElementsByTagName('ul')[0],
		current_display = ul.style.display, display;

		if( current_display === "" || current_display === "block" ){
			display = "none";
			src += "folder.png";
			add_folder_img.addClass("hide");
		}else{
			display = "block";
			src += "folder-open.png"
			add_folder_img.removeClass("hide");;
		}
		ul.style.display = display;
		element.src = src;
	}
}

function deleteResource( elm ){
	var message = "Are You Sure You Want to Delete This Resource?";
	showConfirm( message, false, elm, function(element){ //calback function fired if yes is selected
		var file_path = element.getAttribute( 'data-filepath' ),//path from file from /main root	
		send={ "file_path":file_path };
		controller.postJson( constants.ajax_url+'?action=9', send, function(d){
			//var resp = JSON.parse( d);
			if( d !== "" ){
				var resp = JSON.parse( d );
				if( resp.result ){
					var li = element.nearestParent("li").remove();
				}
				showAlertMessage( resp.message, resp.result );
				
			}else{
				showAlertMessage( "No Data Error", false );
			}
		})
	})	
}

function pictureClick( element ){
	var path = element.getAttribute( 'data-picturepath' );
	//tab_actions.tabShow( document.querySelector('[data-tab=template]') ), //from extender_tabs.js
	window.location.hash = "#template";
	template_item = templatetype[ "image" ]( path );
	gEBI("template").appendChild( template_item );
}

function audioClick( element ){
	var path = element.getAttribute( 'data-audiopath' );
	//tab_actions.tabShow( document.querySelector('[data-tab=template]') ), //from extender_tabs.js
	window.location.hash = "#template";
	template_item = templatetype[ "audio" ]( path );
	gEBI("template").appendChild( template_item );
}

function newFolder( element ){
	var path = element.getAttribute( 'data-folderpath' ),
	parent_li = element.nearestParent('li'),
	folder_list = parent_li.querySelector( "ul.folders" ),
	folder_first_child = folder_list.firstElementChild;
	console.log(folder_first_child);
	if( folder_list !== null && ( folder_first_child === null || !folder_first_child.hasClass("add-folder-li") ) ){ //first child of parent li will eiter be null 'empty' or it will not be an add folder element
		var add_folder_li = createElement( "li", {
			"class":"add-folder-li",
			"child":multiFragment({
				"input":createElement( "input",{
					"type":"text",
					"name":"folder_name"
				}),
				"hidden_path_input":createElement( "input",{
					"type":"hidden",
					"name":"folder_path",
					"value":path
				}),
				"save":createElement( "img",{
					"src":constants.base_url+"/style/resources/confirm_check.png",
					"title":"Save New Folder",
					"events":{
						"click":addFolderAction
					}
				}),
				"remove":createElement( "img",{
					"src":constants.base_url+"/style/resources/action_delete.png",
					"title":"Cancel Add New Folder",
					"events":{
						"click":function(){
							this.nearestParent("li").remove();			
						}
					}
				})				
			})
		});
		
		folder_list.prepend( add_folder_li );	
	}
	
	//tab_actions.tabShow( document.querySelector('[data-tab=template]') ), //from extender_tabs.js
	//window.location.hash = "#template";
	//template_item = templatetype[ "audio" ]( path );
	//gEBI("template").appendChild( template_item );
}

function folderUpload( element ){
	var path = element.getAttribute( 'data-folderpath' ),
	input = gEBI( 'upload-path' );
	input.value = path;
}

function savePopupFolderClickAction( element ){
	var path = element.getAttribute( 'data-folderpath' ),
	form = element.nearestParentClass( "form" ),
	input = form.querySelector( "input[name=folder_path]" );
	input.value = path;
}

function imageUploadValidator(){
	var upload_path_not_empty = (gEBI( 'upload-path' ).value.length > 0 )? true : false,
	file_input_not_empty = ( document.querySelector("input[type='file']").value.length > 0 )? true : false;
	if( upload_path_not_empty && file_input_not_empty ){
		return true;
	}else{
		//if upload_path is not empty then it must be gfile that is
		var message;
		if( !upload_path_not_empty ){
			message = "Folder Form Value is Empty";
		}else{
			message = "Please Select a File To Upload";
		}
		
		showAlertMessage( message, false );
		return false;
	}
}

function uploadResponseAction( obj ){
	if( obj.result === true ){
		var folder_input = gEBI( 'upload-path' ),
		folder_path = folder_input.value,
		folder_element = document.querySelector('[data-filepath="'+folder_path+'"]'),
		ul = folder_element.nearestParent("li").getElementsByTagName("ul");
		
		if( ul.length > 0 ){
			ul[0].innerHTML += obj.data;
		}else{
			listFile( folder_element );
		}
		//clear form 
		folder_input.value = "";
		document.querySelector("input[type='file']").value = "";
	}else{
		showAlertMessage( obj.message, obj.result );
	}
}

function addFolderAction( e ){
	var elm = e.currentTarget,
	parent_li = elm.nearestParent("li"),
	form_class = new FormClass( parent_li ),
	vals = form_class.getValues(),
	message = "Are You Sure You Want to add folder "+vals.folder_path+'/'+vals.folder_name;
	showConfirm( message, false, elm, function(element){ //calback function fired if yes is selected
		//var file_path = element.getAttribute( 'data-filepath' ),//path from file from /main root	
		send=vals;
		controller.postJson( constants.ajax_url+'?action=13', send, function(d){
			var resp = JSON.parse( d);
			if( resp.result ){
				var holder_ul = createElement("ul");
				holder_ul.innerHTML = resp.data;
				elm.nearestParent("li").replaceWith( holder_ul.firstElementChild ); //resp returns new folder li ready to go just replace box with DOM friendly li
			}else{
				showAlertMessage( resp.message, false );
			}
		})
	})	
}

function imageOver( element ){
	var src = element.getAttribute( "data-picturepath" ),
	parent_li = element.nearestParent( "li" );
	parent_li.style.position = "relative";
	var left = element.clientWidth+element.offsetLeft,
	top = element.clientHeight+element.offsetTop,
	pic = createElement("img",{
		"data-imgpreview":"",
		"src":src
	});
	pic.style.position = "absolute";
	pic.style.left = left+"px";
	pic.style.top =  top+"px";
	pic.style.height =  100+"px";
	pic.style.border =  "4px solid white";
	parent_li.appendChild( pic );
}

function imageOut( element ){
	var parent_li = element.nearestParent( "li" ),
	img = parent_li.querySelectorAll( "[data-imgpreview]" );
	console.log( img );
	img.each( function( pic ){ pic.remove() });
	removeInlineStyle( parent_li, 'position' );
}

