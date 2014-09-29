window.base_url=window.location.protocol+"//"+window.location.host ;
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


