window.managerExtraActions = {
	"logout":function(){
		var message = "Are you sure you wish to logout, all unsaved changes will be lost";
		showConfirm( message, false, null, function(elm){ 
			gEBI('logout').submit();
		})
	},
	"posts_tab_action":function( tab, panel ){
	    if( edit_mode.active() ){
	        var posts_tab_being_edited = gEBI(edit_mode.id_in_edit);
	        if( posts_tab_being_edited !== null ){
	            window.scroll(0, posts_tab_being_edited.offsetTop);
	        }   
	    }  
	}	
}

//init tabs, code in extender_new_tabs.js
addEvent( window, "load", function(){
	window.tab_actions = {
		"pictures":function( panel, tab ){
			//initiate resources folder explorer 
			var ul = createElement("ul",{
				"class":"folders"
			});
			ul.innerHTML = bindMustacheString( resources_templates.folder, {base_name:constants.resources_directory.substr(1), file_path:constants.resources_directory} );
			panel.querySelector("div:nth-of-type(1)").appendChild(ul);
			delete this.pictures;
		},	
		"posts":function( tab, panel ){
			//load table page once then overwrite this funtion and check for a post being edited and scroll to it 
			loadTablePage(); //tab_manager.js
			this.posts = managerExtraActions.posts_tab_action;
		},
		"analytics":function( panel, tab ){
			var unique_url_ul = panel.querySelector("div.left > ul");			
			getUniqueUrlPage( unique_url_ul ); //manager/analytics_graphs.js
			getGraphPage();
			delete this.analytics;	
		}	
	}		
	window.tabset = new TabSet( document.body, tab_actions );
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

