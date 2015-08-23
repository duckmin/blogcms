(function(window){
   
    function loadImgs( article ){
        var imgs = article.querySelectorAll("img[data-src]");
        console.log( imgs );
        for( var i = 0, L = imgs.length; i < L; i+=1 ){
            var src = imgs[i].getAttribute("data-src");
            imgs[i].src = src;
        }    
    }
    
    function scrollerMotion(e){
        var item = document.querySelector(".main > article:not([data-loaded])"); //get article not loaded yet
        console.log(item);
        if( item !== null ){
            var space_above_post = item.offsetTop,
            win_height = window.innerHeight,
            bottom_of_window_to_top = (  win_height +  window.pageYOffset  ),
            amt_post_visable = ( bottom_of_window_to_top - space_above_post ); 
            if( amt_post_visable >= 0 ){
               item.setAttribute("data-loaded", "");
               loadImgs( item );
            }
        }else{
           removeEvent( window, "scroll", scrollerMotion ); 
        }
    }
    
    addEvent( window, "load", function(){
        scrollerMotion();  //run once to load any posts images that are visable
        addEvent( window, "scroll", scrollerMotion );
    });
    
})(window);
