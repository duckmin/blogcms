//for this to work you need html and tab setup you need ul with 'data-tab' attribue
//the data-tab attribute must match the panel attribute 'data-tabsection' you wish to show on the tab click
//tab actions are set no need for adjustments
var tab_actions={

	showSelectedTab:function( tab_name ){
		var sections = document.body.querySelectorAll( "[data-tabsection]" ),
		selected_panel;
		sections.each( function( tab_panel ){
			var name = tab_panel.getAttribute( 'data-tabsection' );
			if( name === tab_name ){
				tab_panel.addClass("selected-section");
				selected_panel = tab_panel;
			}else{
				tab_panel.removeClass("selected-section");
			}
		})
		return selected_panel
	},
	
	tabShow:function(clicked_tab){
		var panel_name = clicked_tab.getAttribute( 'data-tab' );
		tabs = clicked_tab.parentElement.querySelectorAll( "[data-tab]" )
		tabs.each( function( tab ){
			tab.removeClass("selected-tab");
		});
		clicked_tab.addClass('selected-tab');
		var panel = tab_actions.showSelectedTab( panel_name );
		
		if( tab_callbacks.hasOwnProperty( panel_name ) && typeof tab_callbacks[ panel_name ] === 'function' ){
			tab_callbacks[ panel_name ]( clicked_tab, panel );
		}
	},
	
	topTabClickAction:function(e){
		var clicked_tab = e.currentTarget;
		tab_actions.tabShow( clicked_tab );
	}
	
}

//you must name the property after the tb you wish to run the callback on
//the callback must inclue 2 arguments the tab element clicked and the panel element shown
//this could be useful for running an ajax onclick to load data only when tab is clicked 
var tab_callbacks = {
	"posts":function( tab, panel ){
		if( !tab.hasAttribute( "data-loaded" ) ){
			loadTablePage();
			tab.setAttribute( "data-loaded", "" );
		}
	},
	"tab4":function( tab, panel ){
		console.log(tab);
		console.log(panel);
		//alert('tab4callback');
	}
}

addEvent( window, "load", function(){
	var tabs = document.body.querySelectorAll( "[data-tab]" )
	tabs.each( function( tab ){
		tab.addEvent( "click", tab_actions.topTabClickAction );
	});
})