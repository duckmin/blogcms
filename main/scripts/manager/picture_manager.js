
function listFile( element ){
	var parent_li = element.nearestParent( "li" ),
	src = constants.base_url+"/style/resources/";
	
	if( element.hasAttribute( 'data-filepath' ) && !element.hasAttribute( 'data-loaded' ) ){
		var path = element.getAttribute( 'data-filepath' ),
		action_num = element.getAttribute( 'data-actionnum' );
		
		controller.getText( constants.ajax_url+'?action='+action_num+'&dir_path='+path, function(d){
			if( d.length > 0 ){
				var list = createElement("ul", {
					"class":"folders",
					"innerHTML":d
				})
				parent_li.appendChild( list );
				element.setAttribute( 'data-loaded', "" );
				element.src = src+= "folder-open.png"; 
			}
		})
		
	}else{
		var ul = parent_li.getElementsByTagName('ul')[0],
		current_display = ul.style.display, display;

		if( current_display === "" || current_display === "block" ){
			display = "none";
			src += "folder.png";
		}else{
			display = "block";
			src += "folder-open.png";
		}
		ul.style.display = display;
		element.src = src;
	}
}

function deleteResource( element ){
	if( confirm("Are You Sure You Want to Delete This Resource?") ){	
		var file_path = element.getAttribute( 'data-filepath' ),//path from file from /main root	
		send={ "file_path":file_path };
		controller.postJson( constants.ajax_url+'?action=9', send, function(d){
			//var resp = JSON.parse( d);
			if( d !== "" ){
				var resp = JSON.parse( d );
				if( resp.result ){
					var li = element.nearestParent("li").remove();
				}
				alert( resp.message )
				
			}else{
				alert( "No Data Error" );
			}
		})
	}
}

function pictureClick( element ){
	var path = element.getAttribute( 'data-picturepath' );
	tab_actions.tabShow( document.querySelector('[data-tab=template]') ), //from extender_tabs.js
	template_item = templatetype[ "image" ]( path );
	gEBI("template").appendChild( template_item );
}

function audioClick( element ){
	var path = element.getAttribute( 'data-audiopath' );
	tab_actions.tabShow( document.querySelector('[data-tab=template]') ), //from extender_tabs.js
	template_item = templatetype[ "audio" ]( path );
	gEBI("template").appendChild( template_item );
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
	if( gEBI( 'upload-path' ).value.length > 0 ){
		return true;
	}else{
		alert( "Folder Form Value is Empty" );
		return false;
	}
}

function uploadResponseAction( obj ){
	if( obj.result === true ){
		var folder_path = gEBI( 'upload-path' ).value,
		folder_element = document.querySelector('[data-filepath="'+folder_path+'"]'),
		ul = folder_element.nearestParent("li").getElementsByTagName("ul");
		
		if( ul.length > 0 ){
			ul[0].innerHTML += obj.data;
		}else{
			listFile( folder_element );
		}
	}else{
		alert( obj.message );
	}
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

