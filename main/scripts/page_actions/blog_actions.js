
function timeFormatter( h, m ){
	var ext = ( h>=12 )? "PM" : "AM";
	( h > 12 )? h = h-12 : false;
	( h === 0 )? h = 12 :false;
	var mins=( m<=9 )? "0"+m : m;
	return h+':'+mins+' '+ext;
}

function convertTimeStamps( element ){
	var months=['January','Febuary','March','April','May','June','July','August','September','October','November','December'],
	days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];	
	element.querySelectorAll("[data-ts]").each(function(li){
		var ts = li.getAttribute("data-ts"),
		d = new Date();
		d.setTime( parseInt(ts) );
		var month = months[ d.getMonth() ],
		date = d.getDate(),
		year = d.getFullYear(),
		hours = d.getHours(),
		mins = d.getMinutes(),
		time = timeFormatter( hours, mins );
		li.innerHTML = month+" "+date+", "+year+"  "+time;
	})
}

var sort_control = {
    getCookie:function(name) {
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length == 2){ 
            return parts.pop().split(";").shift();
        }else{
            return null;    
        }
    },
    isSortActive:function(){
        return ( this.getCookie("sort") !== null )? true : false;
    },
    activateSortOldestToNewest:function(){
        document.cookie = "sort=1; path=/";
    },
    disableSortOldestToNewest:function(){
        //remove cookie so sorting will go back to normal
        document.cookie = "sort=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/"; 
    }
}

addEvent( window, "load", function(){
	attributeActions( document.body, "data-blogaction", {
		"sort":function(sort_button){
			sort_button.addEvent( "click", function(){
			    if( !sort_control.isSortActive() ){
			       //cookie does not exist set cookie for page reload
			      sort_control.activateSortOldestToNewest();
			    }else{
			       sort_control.disableSortOldestToNewest(); 
			    }
			    window.location.reload();
			})
		},
		"category-link":function(cat_link){
			cat_link.addEvent( "click", function(e){
			    if( sort_control.isSortActive() ){
    			    e.preventDefault();
    			    sort_control.disableSortOldestToNewest(); 
    			    window.location.href = this.href;
			    }
			})
		},
		"search-submit":function(search_form){
			search_form.addEvent( "submit", function(e){
                if( sort_control.isSortActive() ){
    			    e.preventDefault();
    			    sort_control.disableSortOldestToNewest(); 
    			    this.submit();
			    }
			})
		}
	});
	
	//take the data-ts (UTC) attribute of every artical and convert to local time
	convertTimeStamps( document );
})
