window.base_url=window.location.protocol+"//"+window.location.host+"/templater/main";
window.constants={
	"base_url":base_url,
	"ajax_url":base_url+"/ajax.php",
	"ajax_in_progress":false
}

window.controller={
	"postJson":function( url, reply_obj, callback ){
		var self=this;
		if( !constants.ajax_in_progress ){
		
			constants.ajax_in_progress=true;
			Ajaxer({
				method:'POST',
				url:url,
				send: encodeURIComponent('json')+'='+encodeURIComponent( JSON.stringify( reply_obj ) ),
				success:function( d ){
					constants.ajax_in_progress=false;
					callback(d);
				},
				error:function( e_code, e_message ){
					constants.ajax_in_progress=false;
					alert(  e_code+" "+e_message );
				}
			})
		}
	},
	
	"getText":function( url, callback ){
		var self=this;
		if( !constants.ajax_in_progress ){
		
			constants.ajax_in_progress=true;
			Ajaxer({
				method:'GET',
				url:url,
				success:function( d ){
					constants.ajax_in_progress=false;
					callback(d);
				},
				error:function( e_code, e_message ){
					constants.ajax_in_progress=false;
					alert(  e_code+" "+e_message );
				}
			})
		}
	}
}

window.box_action = {
	"centerFixedBox":function( element ){
		var elm_width = element.clientWidth,
		height = (element.clientHeight/2)*-1,
		width = (elm_width/2)*-1;
		element.style.marginTop=height+'px';
		element.style.marginLeft=width+'px';
	}
}

function removeInlineStyle( element, property ){
	if ( element.style.removeProperty ) {
		element.style.removeProperty( property );
	}else{
		element.style.removeAttribute( property );
	}
}

/*
window.input_actions={
	"centerMessageDisplay":function ( message ){  //only called on manage page uses class from create_style.php
		var div=createElement('div',{
			"class":"fixed-message",
			"child":createElement('h1',{
				"text":message
			})
		});
		var append=document.body.appendChild( div );//,
		elm_width=append.clientWidth,
		height=(append.clientHeight/2)*-1,
		width=(elm_width/2)*-1;
		append.style.width=elm_width+'px';
		append.style.marginTop=height+'px';
		append.style.marginLeft=width+'px';
		setTimeout( function(){
			append.remove();
		},1800 )
	},
	
	"dispayFormErrorMessageInLoginError":function( form, message ){
		var login_error=form.getElementsByClassName("login-error");
		if( login_error.length > 0 ){
			var text_node=textNode( message );
			login_error[0].removeChildren().appendChild( text_node );
		}
	}
}*/