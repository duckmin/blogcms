function searchClear( e ){
	var target = e.currentTarget;
	target.removeClass("gray-text");	
	target.value = "";
	var d= target.removeEvent( "focus", searchClear );
}

addEvent( window, "load", function(){
	attributeActions( document.body, "data-blogaction", {
		"search-clear":function(elm){
			elm.addEvent( "focus", searchClear );
		}
	})
})