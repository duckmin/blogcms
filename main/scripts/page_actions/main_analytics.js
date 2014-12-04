(function(window){

addEvent(window, "beforeunload", function(e){
	var send = encodeURIComponent("url")+"="+encodeURIComponent(window.location.href);	
	Ajaxer({
		url:constants.ajax_url+'?action=10',
		method:"POST",
		send:send,
		async:false,
		success:function( data ){  },
		error:function( e_code, e_message ){  }
	})
})



})(window);

