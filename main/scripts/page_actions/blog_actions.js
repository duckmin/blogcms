function searchClear( e ){
	var target = e.currentTarget;
	target.value = "";
	var d= target.removeEvent( "click", searchClear );
}

addEvent( window, "load", function(){
	attributeActions( document.body, "data-blogaction", {
		"search-clear":function(elm){
			elm.addEvent( "click", searchClear );
		}
	})
})