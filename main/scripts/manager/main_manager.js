window.managerExtraActions = {
	"logout":function(){
		var message = "Are you sure you wish to logout, all unsaved changes will be lost";
		showConfirm( message, false, null, function(elm){ 
			gEBI('logout').submit();
		})
	}
}

addEvent( window, "load", function(){
	//tab_actions.tabShow( document.querySelector('[data-tab=template]') );
	setMultiSelects( document.querySelector('#save-preview-popup') );
	
	attributeActions( document.body, "data-loadaction", {
			
			"logout":function(elm){
				elm.addEvent( "click", managerExtraActions.logout )
			}
	});
})