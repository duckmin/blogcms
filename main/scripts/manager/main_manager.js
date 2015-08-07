window.managerExtraActions = {
	"logout":function(){
		var message = "Are you sure you wish to logout, all unsaved changes will be lost";
		showConfirm( message, false, null, function(elm){ 
			gEBI('logout').submit();
		})
	}
}

//init tabs, code in extender_new_tabs.js
addEvent( window, "load", function(){
	var actions = {
		"pictures":function( panel, tab ){
			//initiate resources folder explorer 
			var ul = createElement("ul",{
				"class":"folders"
			});
			ul.innerHTML = bindMustacheString( resources_templates.folder, {file_path:constants.resources_directory} );
			panel.appendChild(ul);
			delete this.pictures;
		},	
		"posts":function( tab, panel ){
			loadTablePage(); //tab_manager.js
			delete this.posts;	
		},
		"analytics":function( panel, tab ){
			var unique_url_ul = panel.querySelector("div.left > ul");			
			getUniqueUrlPage( unique_url_ul ); //manager/analytics_graphs.js
			getGraphPage();
			delete this.analytics;	
		}	
	}		
	window.tabset = new TabSet( document.body, actions );
	tabset.init();
})

addEvent( window, "load", function(){
	//tab_actions.tabShow( document.querySelector('[data-tab=template]') );
	setMultiSelects( document.querySelector('#save-preview-popup') );
	
	attributeActions( document.body, "data-loadaction", {
			
			"logout":function(elm){
				elm.addEvent( "click", managerExtraActions.logout )
			}
	});
});

