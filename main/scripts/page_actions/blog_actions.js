
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

var blogloadaction = {};

blogloadaction.makeEmbed = function(elm){
   	var src = elm.src,
   	flash_vars = 'config={"autoPlay":false,"autoBuffering":false,"showFullScreenButton":false,"showMenu":false,"videoFile":"'+src+'","loop":false,"autoRewind":true}',
   	embed = createElement("embed", {
        flashvars:flash_vars,
        pluginspage:'http://www.adobe.com/go/getflashplayer',
        quality:'high',
        allowscriptaccess:'always', 
        allowfullscreen:'true', 
        bgcolor:'#ffffff', 
        src:'/scripts/FlowPlayerClassic.swf', 
        type:'application/x-shockwave-flash'
   	}),
   	audio = elm.parentElement;
    audio.replaceWith(embed);				
}

addEvent( window, "load", function(){
	/*attributeActions( document.body, "data-blogaction", {
		"audio":function(audio_element){
			
		}
	});*/
	
	convertTimeStamps( document );
})
