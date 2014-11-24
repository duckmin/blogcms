(function(window){
	
	window.EDIT_MODE = false;
	window.ID_IN_EDIT = null; 
	window.FOLDER_PATH_IN_EDIT = null; 
	
	function removeBox( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li');
		container.remove();
	}

	function moveUp( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li');
		try{
			var prevli=container.prevElement();
			prevli.appendBefore( container );
		}catch(e){
			container.nearestParent('ul').appendChild( container );
		}
	}

	function moveDown( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li');
		try{
			var prevli=container.nextElement();
			prevli.appendAfter( container );
		}catch(e){
			container.nearestParent('ul').prepend( container );
		}
	}

	function post( posttype, child ){
		return createElement('li',{
			"data-posttype":posttype,
			"child":multiFragment({
				"buttons":createElement('ul',{
					
					"child":multiFragment({
						"moveup":createElement('li',{
							"class":"up-arrow",
							"title":"Move Up",
							"events":{
								"click":moveUp
							}
						}),
						"movedown":createElement('li',{
							"class":"down-arrow",
							"title":"Move Down",
							"events":{
								"click":moveDown
							}
						}),
						"remove":createElement('li',{
							"class":"remove-item",
							"title":"Remove",
							"events":{
								"click":removeBox
							}
						})
					})
				}),
				"container":createElement('div',{
					"class":"tmplt-forum-container",
					"child":multiFragment({
						"input":createElement('input',{
							"type":"hidden",
							"name":"data-posttype",
							"value":posttype
						}),
						"selections":child
					})
				})
				
			})
		})
	}
	
	function addLink( e ){
		var element = e.srcElement||e.currentTarget;
		var container = element.nearestParent('div'),
		textarea = container.querySelectorAll('textarea[name=text]')[0],
		value = textarea.value;
		textarea.value = value + " []()";
	}
	
	function previewImage( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('div'),
		input=container.querySelectorAll('input[name=src]')[0],
		frame=container.getElementsByTagName('img')[0];
		if( typeof frame === "undefined" ){
			frame = container.appendChild( createElement('img' , {"src":"#"} ) );
		}
		frame.src=input.value; 
	}
	
	function previewVideo( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('div'),
		youtube_pat=/^(http|https):\/\/www.youtube.com\/watch\?v=(.+)$/,
		input=container.querySelectorAll('input[name=src]')[0],
		src=input.value,
		frame=container.getElementsByTagName('iframe')[0];
		if( typeof frame === "undefined" ){
			container.appendChild( createElement('iframe') );
			frame=container.getElementsByTagName('iframe')[0];
			console.log( frame );
		}
		
		if( youtube_pat.test( src ) ){
			src=src.replace( youtube_pat, "http://www.youtube.com/embed/$2" );
			input.value=src;
		}
		frame.src=src; 
	}
	
	window.templatetype = {
		"markdown":function( text ){
			return post( "markdown", multiFragment({
				"heading":createElement('h5',{
					"text":"Markdown"
				}),
				"input":createElement('textarea',{
					"name":"text"
				}),
				"button":createElement('ul',{
					"class":"button-list",
					"child":multiFragment({
						"preview":createElement('li',{
							"text":"Add Link",
							"events":{
								"click":addLink
							}
						})
					})
				})
			}))
		},
		"image":function( src ){
			return post( "image", multiFragment({
					"heading":createElement('h5',{
						"text":"Image Embed"
					}),
					"input":createElement('input',{
						"name":"src",
						"type":"text",
						"value":src||""
					}),
					"button":createElement('ul',{
						"class":"button-list",
						"child":multiFragment({
							"preview":createElement('li',{
								"text":"Preview Img",
								"events":{
									"click":previewImage
								}
							})
						})
					})
				})
			)
		},
		"video":function(){
			return post("video", multiFragment({
					"heading":createElement('h5',{
						"text":"Video Embed"
					}),
					"input":createElement('input',{
						"name":"src",
						"type":"text"
					}),
					"button":createElement('ul',{
						"class":"button-list",
						"child":multiFragment({
							"preview":createElement('li',{
								"text":"Preview",
								"events":{
									"click":previewVideo
								}
							})
						})
					})
				})
			)
		}
	}
	
	//controls save button after previewing template
	window.edit_mode = {
		"enable":function( id, folder ){
			EDIT_MODE = true;
			ID_IN_EDIT = id; 
		},
		"disable":function(){
			EDIT_MODE = false;
			ID_IN_EDIT = null; 
		},
		"active":function(){
			return ( EDIT_MODE === false && ID_IN_EDIT === null )?
			false : true;
		}
	}
	
	window.getPostDataFromTemplate = function(){
		var posttypes = gEBI("template").querySelectorAll("li[data-posttype]"),
		holder = [];
		
		if( posttypes.length > 0 ){
			posttypes.each( function( template_item ){
				var form_class = new FormClass( template_item ),
				values = form_class.getValues();
				holder.push( values );
			});
		}
		return holder
	}
	
	function previewPost(){
		var post_data = getPostDataFromTemplate();
		console.log( post_data );
		
		if( post_data.length > 0 ){
			controller.postJson( constants.ajax_url+'?action=1', post_data, function(d){
				console.log( d );
				if( d.length > 0 ){
					gEBI("preview").innerHTML = d;
					tab_actions.tabShow( document.querySelector('[data-tab=preview]') );
				}
			})
		}else{
			alert("Template is Empty");
		}
		//console.log( holder );
	}
	
	function savePost( save_form ){
		var post_data = getPostDataFromTemplate(),
		form_class = new FormClass( save_form ),
		values = form_class.getValues();
		console.log( values );
		
		if( post_data.length > 0/*safty check*/ ){
			values.post_data = post_data;
			controller.postJson( constants.ajax_url+'?action=3&procedure=1', values, function(d){
				var resp = JSON.parse( d);
				if( resp.result){
					form_class.clearForm();
					save_form.nearestParentClass("dark-shade").addClass("hide");
					gEBI('template').removeChildren();
					tab_actions.tabShow( document.querySelector('[data-tab=template]') );
					
				}
				alert( resp.message );
			})
		}else{
			alert("Folder is Empty");
		}
	}
	
	addEvent( window, "load", function(){
		attributeActions( document.body, "data-templateaction", {
			
			"additem":function(elm){
				elm.addEvent( "click", function(e){
					var action = elm.getAttribute("data-action"),
					template_item = templatetype[ action ]();
					gEBI("template").appendChild( template_item );
				})
			},
			"preview-post":function(elm){
				elm.addEvent( "click", function(e){
					previewPost();
				})
			},
			"cancel-template":function(elm){
				elm.addEvent( "click", function(e){
					var edited = edit_mode.active(),
					message = ( !edited )? "Are you sure you want to clear the template?" : //not in edit
					"Post is currently being edited and all changes will will lost if canceled are you sure you want to clear the template?";
					if( confirm( message ) ){
						( edited )? edit_mode.disable() : false;
						gEBI("template").removeChildren();	
					}					
				})
			},
			"save-preview":function(elm){
				
				elm.addEvent( "click", function(e){
					if( !edit_mode.active() ){
						var pop_up = gEBI( 'save-preview-popup' );
						pop_up.removeClass('hide');
						box_action.centerFixedBox( pop_up.querySelector('div.fixed-box') );
					}else{
						saveEditedPostAction();
					}
				})
				
			},
			"close-popup":function(elm){
				elm.addEvent( "click", function(e){
					var parent_shade = elm.nearestParentClass("dark-shade");
					parent_shade.addClass('hide');
				})
			},
			"save-new-post":function(elm){
				elm.addEvent( "click", function(e){
					var save_form = elm.nearestParentClass( "form" );
					savePost( save_form );
				})
			},
			"select-post-filter":function(elm){
				elm.addEvent( "click", function(e){
					POSTS_TABLE_PAGENUM = 1;
					loadTablePage();
				})
			}
		})
	})

})(window);

//posts edit section
(function(window){

	window.POSTS_TABLE_PAGENUM = 1;
	var edit_table_template="<tr data-postid='{{ id }}' >"+
	"<td>"+
		"<select name='category' multiple='' >{{ post_type_options }}</select>"+
	"</td>"+
	"<td><textarea name='description' >{{ description }}</textarea></td>"+
	"<td>"+
		"<input type='hidden' name='id' value='{{ id }}' />"+
		"<input type='text' name='title' value='{{ title }}' />"+
	"</td>"+	
	"<td class='date' >{{ created }}</td>"+
	"<td>"+
		"<img src='"+constants.base_url+"/style/resources/save.png' title='Save Changes' onclick='saveChangesAction( this )' />"+
		"<img src='"+constants.base_url+"/style/resources/pencil.png' title='Edit Post' onclick='editPostAction( this )' />"+
		"<a href='"+constants.base_url+"/post/{{ id }}' >"+
			"<img src='"+constants.base_url+"/style/resources/application.png' title='View Post' />"+
		"</a>"+
		"<img src='"+base_url+"/style/resources/action_delete.png' title='Delete Post' onclick='deletePostAction( this )' />"+
	"</td>";
	
	window.loadTablePage = function(){
		var section = document.querySelector('[data-tabsection=posts]'),
		tbody = section.querySelector('table > tbody'),
		nav = section.querySelector('ul.list-nav'),
		category_selection = section.querySelector('ul.inline-list'),
		nav_body = documentFragment(),
		cat_form_class = new FormClass( category_selection ),
		//get value of the radio filter and add to URL so mongo can sort					
		cat_value = cat_form_class.getValues().blog_grid_sort;	
			
		controller.getText( constants.ajax_url+'?action=4&p='+POSTS_TABLE_PAGENUM+'&cat='+cat_value, function(d){
			if( d.length > 0 ){
				var json = JSON.parse( d );
				if( json.result === true ){
					var post_data = JSON.parse( json.data.posts ),
					inside_tbody="";
					post_data.forEach( function( single_row ){
						inside_tbody += bindMustacheString( edit_table_template, single_row );
					})
					
					if( json.data.prev===true ){
						var prev=createElement('li',{
							text:"Prev",
							events:{
								"click":function(){
									POSTS_TABLE_PAGENUM -= 1;
									loadTablePage();
								}
							}
						});
						nav_body.appendChild( prev  )
					}
					
					if( json.data.next===true ){
						var next=createElement('li',{
							text:"Next",
							events:{
								"click":function(){
									POSTS_TABLE_PAGENUM += 1;
									loadTablePage();
								}
							}
						});
						nav_body.appendChild( next )
					}
					
					tbody.innerHTML = inside_tbody;
					nav.removeChildren();
					nav.appendChild( nav_body );
					
				}else{
					alert( resp.message );
				}
			}
		})
	}
	
	var table_actions = {
		getTrValues:function( element ){
			var tr=element.nearestParent("tr"),
			form_obj=new FormClass( tr ),
			form_values=form_obj.getValues();
			return form_values;
		}
	}
	
	window.saveChangesAction = function( element ){
		var form_values=table_actions.getTrValues( element );
		
		controller.postJson( constants.ajax_url+'?action=3&procedure=2', form_values, function(d){
			var resp = JSON.parse( d);
			alert( resp.message );
		})
	}
	
	window.deletePostAction = function( element ){
		if( confirm("Are you sure you want to delete this post?") ){
			var form_values=table_actions.getTrValues( element ),
			send={ "id":form_values.id, "folder_path":form_values.folder_path };
			controller.postJson( constants.ajax_url+'?action=5', send, function(d){
				var resp = JSON.parse( d);
				if( resp.result ){
					loadTablePage();
				}else{
					alert( resp.message );
				}
			})
		}
	}
	
	window.editPostAction = function( element ){
		var form_values=table_actions.getTrValues( element ),
		send={ "id":form_values.id };
		controller.postJson( constants.ajax_url+'?action=6', send, function(d){
			//var resp = JSON.parse( d);
			if( d !== "" ){
				var resp = JSON.parse( d ),
				frag = documentFragment();
				resp.forEach(function( post ){
					var post_type = post["data-posttype"],
					li = templatetype[ post_type ](),
					form_class = new FormClass( li );
					form_class.bindValues( post );
					frag.appendChild( li );
					edit_mode.enable( form_values.id );
				});
				
				gEBI("template").removeChildren().appendChild(frag);
				tab_actions.tabShow( document.querySelector('[data-tab=template]') );
			}else{
				alert( "No Data Error" );
			}
		})
	
	}
	
	window.saveEditedPostAction = function(){
		var post_data = getPostDataFromTemplate();
		if( post_data.length > 0 ){
			var values = { "id":ID_IN_EDIT, "post_data":post_data }
			console.log( values );
			controller.postJson( constants.ajax_url+'?action=7', values, function(d){
				
				var resp = JSON.parse( d);
				if( resp.result){
					edit_mode.disable(); 
					gEBI('template').removeChildren();
					tab_actions.tabShow( document.querySelector('[data-tab=template]') );
				}
				alert( resp.message );
			})
		}else{
			alert("Empty Post Can Not Be Saved");
		}
	}
	
})(window);