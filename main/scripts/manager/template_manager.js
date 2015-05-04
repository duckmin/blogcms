(function(window){
	
	function removeBox( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li');
		container.remove();
	} 

	function moveUp( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li'),
		prev_li = container.previousElementSibling;
		if( prev_li !== null ){
			prev_li.appendBefore( container );
		}else{
			container.nearestParent('ul').appendChild( container );
		}
	}

	function moveDown( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('li'),
		next_li = container.nextElementSibling;
		if( next_li !== null ){
			next_li.appendAfter( container );
		}else{
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
	
	function previewAudio( e ){
		var element= e.srcElement||e.currentTarget;
		var container=element.nearestParent('div'),
		input=container.querySelectorAll('input[name=src]')[0],
		val = input.value,
		embed = createElement("embed",{
			"height":"22",
			"width":"500",
			"flashvars":'config={"autoPlay":false,"autoBuffering":false,"showFullScreenButton":false,"showMenu":false,"videoFile":"'+val+'","loop":false,"autoRewind":true}',
			"pluginspage":"http://www.adobe.com/go/getflashplayer",
			"quality":"high",
			"allowscriptaccess":"always",
			"allowfullscreen":"true",
			"bgcolor":"#ffffff",
			"src":"scripts/FlowPlayerClassic.swf",
			"type":"application/x-shockwave-flash"
		});		
		frame=container.getElementsByTagName('embed')[0];
		if( typeof frame === "undefined" ){
			frame = container.appendChild( embed );
		}else{
			frame.replaceWith( embed );	
		}
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
					"subheading":createElement('h6',{
						"text":"Alt ( text that appears if image does not load )"
					}),
					"alt_input":createElement('input',{
						"name":"alt",
						"type":"text",
						"value":""
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
		"audio":function( src ){
			return post( "audio", multiFragment({
					"heading":createElement('h5',{
						"text":"Audio Embed"
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
								"text":"Preview Audio",
								"events":{
									"click":previewAudio
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
		"edit_mode":false,
		"id_in_edit":"",
		"getEditForm":function(){
			var form = gEBI("edit-mode-form");		
			return {
				"form":form,
				"form_class":new FormClass( form )	
			};
		},		
		"enable":function( id ){
			var edit_form = this.getEditForm(),
			edit_mode = true,
			id_in_edit = id
			this.edit_mode = edit_mode,
			this.id_in_edit = id_in_edit;			
			edit_form.form_class.bindValues( { "edit_mode":edit_mode, "id_in_edit":id_in_edit} );	
			edit_form.form.removeClass("hide");		
		},
		"disable":function(){
			var edit_form = this.getEditForm();
			edit_form.form_class.clearForm();
			edit_form.form.addClass("hide");	
			this.edit_mode = false;
			this.id_in_edit = ""; 
		},
		"active":function(){		
			return ( this.edit_mode === false && this.id_in_edit === "" )?
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
					//tab_actions.tabShow( document.querySelector('[data-tab=preview]') );
					window.location.hash = "#preview";
				}
			})
		}else{
			showAlertMessage("Template is Empty", false );
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
				if( resp.result ){
					save_form.nearestParentClass("dark-shade").addClass("hide");
					form_class.clearForm();
					save_form.querySelectorAll("ul.multi-replace > li.selected-multi" ).each( function(li){
						li.removeClass("selected-multi");
					} )
					gEBI('template').removeChildren();
					//tab_actions.tabShow( document.querySelector('[data-tab=template]') );
					window.location.hash = "#template";
					
				}
				showAlertMessage( resp.message, resp.result );
			})
		}else{
			showAlertMessage("Template is Empty", false );
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
					"Post is currently being edited and all changes will be lost if canceled are you sure you want to clear the template?";
					showConfirm( message, false, gEBI("template"), function(elm){ 
						( edited )? edit_mode.disable() : false;						
						elm.removeChildren();	
					} )			
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
			},
			"date-picker":function(elm){
				//initialize date picks in calendar.js
				setDatePickers(elm)
			}
		})
	})

//POSTS TAB EDIT FUNCS -----------------------------------------------------------------------------------------------------

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
		"<a href='"+constants.base_url+"/post/{{ first_category }}/{{ id }}' target='_blank' >"+
			"<img src='"+constants.base_url+"/style/resources/application.png' title='View Post' />"+
		"</a>"+
		"<img src='"+constants.base_url+"/style/resources/clock.png' title='Make most recent post (move to top of the)' onclick='postMoveToTop( this )' />"+
		"<img src='"+base_url+"/style/resources/action_delete.png' title='Delete Post' onclick='deletePostAction( this )' />"+
	"</td>";
	
	window.loadTablePage = function(){
		var section = document.querySelector('section[data-tab=posts]'),
		tbody = section.querySelector('table > tbody'),
		nav = section.querySelector('ul.button-list'),
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
					setMultiSelects( tbody );
					nav.removeChildren();
					nav.appendChild( nav_body );
					
				}else{
					showAlertMessage( json.message, json.result );
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
			showAlertMessage( resp.message, resp.result );
		})
	}
	
	window.deletePostAction = function( element ){
		var message = "Are you sure you want to delete this post?";
		showConfirm( message, false, element, function(elm){ //calback function fired if yes is selected
			var form_values=table_actions.getTrValues( element ),
			send={ "id":form_values.id };
			controller.postJson( constants.ajax_url+'?action=5', send, function(d){
				var resp = JSON.parse( d);
				if( resp.result ){
					loadTablePage();
				}
				showAlertMessage( resp.message, resp.result );
			})
		})	
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
				//tab_actions.tabShow( document.querySelector('[data-tab=template]') );
				window.location.hash = "#template";
			}else{
				showAlertMessage( "No Data For Post", false );
			}
		})
	
	}
	
	window.postMoveToTop = function( element ){
		var message = "Are you sure you wish to renew the date on this post,  renewing date will move this post to the top of all categories it is a part of and can not be reversed";		
		showConfirm( message, false, element, function(elm){ //calback function fired if yes is selected
			var form_values=table_actions.getTrValues( element ),
			send={ "id":form_values.id };
			controller.postJson( constants.ajax_url+'?action=8', send, function(d){
				//var resp = JSON.parse( d);
				if( d !== "" ){
					var resp = JSON.parse( d );
					showAlertMessage( resp.message, resp.result );
				}else{
					showAlertMessage( "No Data Error", false );
				}
			})	
		})	
	}
	
	window.saveEditedPostAction = function(){
		var post_data = getPostDataFromTemplate();
		if( post_data.length > 0 ){
			var values = { "id":edit_mode.id_in_edit, "post_data":post_data }
			console.log( values );
			controller.postJson( constants.ajax_url+'?action=7', values, function(d){
				
				var resp = JSON.parse( d);
				if( resp.result){
					edit_mode.disable(); 
					gEBI('template').removeChildren();
					//tab_actions.tabShow( document.querySelector('[data-tab=template]') );
					window.location.hash = "#template";
				}
				showAlertMessage( resp.message, resp.result );
			})
		}else{
			showAlertMessage( "Empty Post Can Not Be Saved", false );
		}
	}
	
})(window);