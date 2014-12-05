//LIB HAS NO DEPENDANCIES FOR ANY OTHER LIB, IT IS STAND ALONE 

var af={};

(function(af){//math functions
	
	function roundNumber(number, digits){
		var multiple = Math.pow(10, digits);
		var rndedNum = Math.round(number * multiple) / multiple;
		return rndedNum;
	}
	
	af.average=function( array ){ //takes num array returns average num
		var sum=af.getSum(array);
		return roundNumber(sum/array.length, 2 )
	}
	
	af.getPercent=function( num, total ){//take a single num and a total num return percentage of total
		if( isType(num,'number') && isType(total,'number') ){
			var division=num/total;
			var percent=division*100;
			return roundNumber(percent, 2)
		}
	}
	
	af.getSum=function( array ){
		if( array instanceof Array ){
			var total=0;
			array.forEach( function( num ){
				( typeof num === 'number' )? total+=num : false;
			})
			return total
		}else{
			throw new Error( "Parameter of af.getSum must be an array of integers" );
		}
	}
	
	af.getPercentages=function( array ){//take an array of numbers, return array of there percent values out of 100
		if( array instanceof Array ){
			var holder=[],
			total=this.getSum( array ),
			percent,
			self=this;
			array.forEach( function( num ){
				if( isType( num, 'number' ) ){
					percent=self.getPercent( num, total );
					holder.push( percent );
				}
			} )
			return holder
		}
	}
	
	af.getLowestNumber=function( num_array ){
		if( num_array instanceof Array ){
			var sum=af.getSum( num_array ), lowest=false;
			( sum < 0 )? sum=sum*-1 : false;
			num_array.forEach(function(num){
				if( typeof num === 'number' ){
					if( num < sum ){
						sum=num;
						lowest=num;
					}
				}
			})
			return lowest
		}else{
			throw new Error( "Parameter of af.getLowestNumber must be an array of integers" );
		}
	}
	
	af.getGreatestNumber=function( num_array ){
		if( num_array instanceof Array ){
			var sum=af.getSum( num_array ), highest=false;
			( sum > 0 )? sum=sum*-1 : false;
			num_array.forEach(function(num){
				if( typeof num === 'number' ){
					if( num > sum ){
						sum=num;
						highest=num;
					}
				}
			})
			return highest
		}else{
			throw new Error( "Parameter of af.getGreatestNumber must be an array of integers" );
		}
	}
  
})(af);



(function(af){
	
	af.months=['January','Febuary','March','April','May','June','July','August','September','October','November','December'];
	af.days=['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
	var mills_in_second=1000;
	var mills_in_minute=mills_in_second*60;
	var mills_in_hour=mills_in_minute*60;
	var mills_in_day=mills_in_hour*24;
	var mills_in_week=mills_in_day*7;
	
	af.timeFormatter=function( h, m, s ){
		var hours=( h<=9 )? "0"+h : h,
		mins=( m<=9 )? "0"+m : m,
		secs=( s<=9 )? "0"+s : s;
		return hours+':'+mins+':'+secs;
	}
	
	function subtractFromTime( utc_hour, diff ){
		for( var i=0; i<diff; i+=1 ){
			if( utc_hour>0 ){
				utc_hour-=1
			}else{
				utc_hour=23
			}
		}
		return utc_hour
	}
	
	//can accept a UTC time or a mm/dd/yyy date
	af.date=function( date ){
		var pre=( typeof date==='number' )? date : Date.parse( date );
		if( !isNaN( pre ) ){
			var d=new Date( pre );
			return {
				day:af.days[ d.getDay() ],
				month:d.getMonth()+1,
				date:d.getDate(),
				year:d.getFullYear(),
				calendar_month:af.months[ d.getMonth() ],
				utc:pre,
				utc_hour:d.getUTCHours(),
				local_hour:subtractFromTime( d.getUTCHours(), ( d.getTimezoneOffset()/60 ) ),
				minutes:d.getUTCMinutes(),
				seconds:d.getUTCSeconds(),
				formatted_date:function(){
					var month_format=( this.month<=9 )? "0"+this.month : this.month,
					date_format=( this.date<=9 )? "0"+this.date : this.date;
					return month_format+'/'+date_format+'/'+this.year;
				},
				formatted_local_time:function(){ return af.timeFormatter( this.local_hour, this.minutes, this.seconds ) },
				formatted_utc_time:function(){ return af.timeFormatter( this.utc_hour, this.minutes, this.seconds ) }
			}
		}else{
			throw new Error( date+" is not a valid date" );
		}
    }
	
	af.UTCParser=function( utc_milliseconds ){
		var weeks=Math.floor( utc_milliseconds/mills_in_week );
		utc_milliseconds-=( weeks*mills_in_week );
		var days=Math.floor( utc_milliseconds/mills_in_day );
		utc_milliseconds-=( days*mills_in_day );
		var hours=Math.floor( utc_milliseconds/mills_in_hour );
		utc_milliseconds-=( hours*mills_in_hour );
		var minutes=Math.floor( utc_milliseconds/ mills_in_minute );
		utc_milliseconds-=( minutes* mills_in_minute );
		var seconds=Math.floor( utc_milliseconds/mills_in_second );
		return {
			weeks:weeks, days:days, hours:hours, minutes:minutes, seconds:seconds
		}
	}
	
	af.timeBetweenUtc=function( old_d, new_d ){

		if( typeof old_d === 'number' && typeof new_d === 'number' ){
			if( new_d > old_d ){

				var diff=new_d - old_d;
				var parsed=this.UTCParser( diff );
				return parsed;

			}else{
				throw 'in timeBetweenDates put older date as first argument and more recent date as seconds argument';
			}
		}else{
			throw 'in timeBetweenDates both arguments must be numeric UTC seconds representations of the dates';
		}
	}
	
	af.getClosestDateAfterToday=function( date_array ){ //takes array of mmddyyyy  and gives returns the next one after todays date
		var now=Date.now(), holder=[];					//returns false if no date found
		date_array.forEach(function(date, index){
			if( typeof date === "number" || af.isValidMMDDYYYY( date ) ){
				var date_utc = af.date( date ).utc,
				diff = date_utc - now; 
				console.log( diff );
				if( diff >= 0 ){
					holder.push( date_utc );
				}
			} 
		})
		return ( holder.length>0 )? af.date( af.getLowestNumber( holder ) ).formatted_date() : false 
	}
	
	af.allDateObjectsInMonthYear=function( month, year ){
		var first=this.date( month+'/1/'+year ),
		day_holder=[first],
		next_day;
		while(true){
			last_day=day_holder[ day_holder.length-1 ]
			next_day=this.date( (last_day.utc+mills_in_day) );
			console.log( parseInt(next_day.utc) );
			if( next_day.month===last_day.month ){				
				day_holder.push( next_day );
			}else{
				break;
			}
		}
		return day_holder;
	}
	
	af.isValidMMDDYYYY=function( date ){
		var pat=/^\d{1,2}[\/-]{1}\d{1,2}[\/-]{1}\d{4}$/;
		return pat.test( date );
	}

})(af);


//DEPANCIES IN ELEMENT EXTENDER.JS AND date_math.js  WILL MOVE AS A STANDALONE PLUGIN ONCE COMPLETE
(function( window ){
	
	//maybe change click to a callback so you can do more than just add click action?
	window.getDaysFrag=function(  month, year, click ){
		var all_days_in_month=af.allDateObjectsInMonthYear( month, year ), day_index=0, day_in_view, tr,
		L=all_days_in_month.length,
		
		cell,
		frag=documentFragment();
		//console.log( all_days_in_month );
		while( day_index< L ){
			tr=createElement( "tr");
			for( var i=0; i<7; i+=1 ){
				day_in_view=all_days_in_month[ day_index ];
				if( typeof day_in_view !== "undefined" && af.days[i]===day_in_view.day ){
					cell=createElement( "td", {
						"data-utc":day_in_view.utc.toString(),
						"data-date":day_in_view.formatted_date(),
						"text":day_in_view.date.toString()
					});
					if( typeof click === 'function' ){ click( cell ) }
					tr.appendChild( cell );
					day_index+=1;
				}else{
					console.log( 'b' )
					tr.appendChild( createElement( "td") );
				}
			}
			frag.appendChild( tr );
		}
		return frag
	}
	
	window.dateBox=function(  month, year, click ){
		return createElement("table",{
			"child":multiFragment({
				"header":createElement("thead",{
					"child":multiFragment({
						/*"month":createElement( "tr", {
							"child":createElement( "th", {
								"colspan":"7",
								"text":af.months[month-1]+" "+year
							})
						}),*/
						"days":createElement( "tr", {
							"child":multiFragment((function(){
								var obj={};
								af.days.forEach( function(day){
									obj[day]=createElement( "th", { "text":day.substr(0,3) } )
								} );
								return obj
							})() )
						})
					})
				}),
				"body":createElement( "tbody", {
					"child":getDaysFrag(  month, year, click )
				})
			})
		})
	}
	
	function monthOptions( selected_month ){
		var frag=documentFragment(), month;
		af.months.forEach( function( month, i ){
			month=createElement( "option", {
				"text":month,
				"value":( i+1 ).toString()
			} );
			( ( i+1 )===selected_month )? month.setAttribute( "selected", "" ) : false;
			frag.appendChild( month );
		} );
		return frag;
	}
	
	function yearOptions( selected_year ){
		var frag=documentFragment(), year, curryear=af.date( Date.now() ).year;
		for( var i=curryear-5; i<curryear+10; i+=1 ){
			year=createElement( "option", {
				"text":i.toString(),
				"value":i.toString()
			} );
			( i===selected_year )? year.setAttribute( "selected", "" ) : false;
			frag.appendChild( year );
		}
		return frag;
	}
	
	//click param is a callback so you can add events to the cells
	/*
		var datebox=new DateBox( 5, 2014, function(cell){
			cell.addEvent( "click", function(e){
				var elm=e.target,
				utc=elm.getAttribute("data-utc"),
				date=elm.getAttribute("data-date");
				console.log( utc+' '+date)
			})
		});
	*/
	window.DateBox=function( month, year, click ){
		this.month=month;
		this.year=year;
		this.container=this.dateSelectorBox( click );
	}
	
	DateBox.prototype.loadNewDate=function( click ){
		var table=this.container.getElementsByTagName('table')[0],
		//th=table.getElementsByTagName('thead')[0].getElementsByTagName('th')[0],
		t_body=table.getElementsByTagName('tbody')[0],
		interior=getDaysFrag(  this.month, this.year, click );
		t_body.removeChildren().appendChild( interior );
		//th.innerHTML=af.months[this.month-1]+" "+this.year
	}
	
	DateBox.prototype.dateSelectorBox=function( click ){
		var self=this,
		container=createElement("div",{
			"class":"date-box",
			"child":multiFragment({
				"month_selector":createElement("select",{
					"child":monthOptions( this.month ),
					"events":{
						"change":function(){
							var selected=this.options[ this.options.selectedIndex ].value;
							self.month=parseInt( selected );
							self.loadNewDate( click );
						}
					}
				}),
				"year_selector":createElement("select",{
					"child":yearOptions( this.year ),
					"events":{
						"change":function(){
							var selected=this.options[ this.options.selectedIndex ].value;
							self.year=parseInt( selected );
							self.loadNewDate( click );
						}
					}
				}),
				"calendar":dateBox(  this.month, this.year, click )
			})
		})
		
		return container
	}

})( window );


//code for date picker
(function(window){

	window.datePickClick=function( e ){
		var element=e.target,
		parent=element.parentElement;
		
		if( parent.querySelector('div.date-box') === null ){
			parent.style.position="relative";
			var width=element.clientWidth,
			height=element.clientHeight,
			left=element.offsetLeft - width,
			top=element.offsetTop + height + 6,
			value=element.value,
			current_date, month, year;
			
			if( af.isValidMMDDYYYY( value ) ){
				current_date=af.date( value );
				month=current_date.month;
				year=current_date.year;
			}else{
				current_date=af.date( Date.now() );
				month=current_date.month;
				year=current_date.year;
			}
			
			var datebox=new DateBox( month, year, function(cell){
				//console.log(cell.getAttribute("data-date"))
				//if date matches todays date or selected date highlight a color
				cell.addEvent( "click", function(e){
					var elm=e.currentTarget,
					utc=elm.getAttribute("data-utc"),
					date=elm.getAttribute("data-date");
					element.value=date;
					cell.nearestParentClass("date-box").remove();
					parent.style.removeProperty('position');
				})
			});
			datebox.container.style.position="absolute";
			datebox.container.style.top=top+"px";
			datebox.container.style.left=( left+width )+"px";
			parent.appendChild( datebox.container );
		}
		
	}
	
	window.datePickBlur=function( e ){
		var element=e.target,
		parent=element.parentElement;
		parent.getElementsByClassName("date-box")[0].remove();
		parent.style.removeProperty('position');
	}
	
	window.setDatePickers=function( element ){
		var elm = element || document;
		elm.querySelectorAll("input[data-datepick]").each( function( input ){
			input.addEvent( "click", datePickClick );
			//input.addEvent( "blur", datePickBlur );
		})
	}

})( window );
