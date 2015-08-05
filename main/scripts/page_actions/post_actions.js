(function(window){

addEvent(window, "load", function(e){
	var ts = document.querySelector("section.main > article:last-of-type > ul.post-head	> li[data-ts]").getAttribute("data-ts");
	var category = document.querySelector("input[name='cat']").value;
	console.log( category );
	Ajaxer({
		url:constants.ajax_url+'?action=14&ts='+ts+'&cat='+category,
		method:"GET",
		send:null,
		async:true,
		success:function( data ){ 
			document.querySelector("section.main").innerHTML+=data; 
		},
		error:function( e_code, e_message ){  }
	})
})



})(window);
