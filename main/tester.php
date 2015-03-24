<?php
	$server = dirname(__FILE__)."/../server";
	
	include_once $server."/configs.php";
	$db = MongoConnection();file:///home/duckmin/projects/js_libs/libs_in_dev/extender_test.html
	//$id2 = new MongoId();
	//echo $id2;
	/*$count = ( 4-1 )*$GLOBALS['amount_on_main_page'];
	$skip = $GLOBALS['amount_on_main_page']+1;
	$collection = $db->blog->posts;			
	$cursor = $collection->find( array( "category"=>'blog' ) )->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );		
	//$collection_count = $collection->count( array( "category"=>'blog' ) );
	echo $collection_count.'<br>';	
	echo $cursor->count(true);*/
	//$id =  "5428a70e7f8b9a3a1b779e93";
	//$mongo_id = new MongoId( $id );
	//$d = new MongoDate();
	//$time_stamp = new MongoDate( $mongo_id->getTimestamp() );
	//echo $time_stamp;
	//$collection = $db->blog->posts;	
	//$cursor = $collection->update( array( '_id'=>$mongo_id ), array( '$set'=> array( "created"=>$d ) ) );
	//$cursor = $collection->find( array( 'created'=>array( '$lt'=>$d ) ) );
	//echo var_dump( $cursor );

	//$parser = new Parsedown();
	
$t = 'Hello _Parsedown_! whus good my ninja
I am a<b>baws</b> of this number
munchin ass niga shit mein!
	
#_I am a king of this shite_';
	
	//echo $parser->text( $t );



	//$getter = new MongoGetter( $db );
	//$one = $getter->getSinglePostDataById( "5428a45a7f8b9afe1a779e95" );
	//echo var_dump( $one );
	
	/*$items = $getter->getHomePagePostsFromDbByCategory( 1, "video" );	
	//$formatter = new PostViews();
	foreach ($items as $document) {
    	//echo print_r( $document )."<br>";
    	//echo $formatter->makePostHtmlFromData( $document );
		$i = new MongoId( $document["_id"] );    	
    	//echo $i->__toString()."<br>";
    	echo $i->getTimestamp()."<br>";
	}
	
	$date =1344988800;
	//$dt = new DateTime("@$date");
	echo date('Y-m-d H:i:s', 1413857240 );
	//header("location:"."http://www.google.com"."?qsss=sajdsakjd");
	//echo print_r( $GLOBALS['url_parts'] );	
	//echo var_dump( $items );
	$base = $GLOBALS['base_url'];
	$url = $_SERVER["REQUEST_URI"];
	$cache = new CacheController( $GLOBALS['cache_dir'], $url );
	if( !$cache->urlInCache()true ){
		
		if( !isset( $_GET['p'] ) ){ 
			$page=1;
		}else{
			$page=(int)$_GET['p'];
		}
		
		$post_views = new PostViews();		
		$cat = ( isset( $_GET['cat'] ) )? $_GET['cat'] : $GLOBALS['post_categories'][0];
		$search = ( isset( $_GET['s'] ) )? $_GET['s'] : null;
		
		$tmplt_data = array();
		$tmplt_data["title"] = "Blog - Page ".$page;
		$tmplt_data["description"] = "";
		$tmplt_data["styles"] = "";
		$tmplt_data["scripts"] = "";
		$tmplt_data["base"] = $base;
		$tmplt_data["header"] = $post_views->getCatHeaderList( $cat );
		
		$db = DBHelper::dbConnection();
		$db_getter = new DbGetter( $db );
		$post_controller = new PostController( $db_getter, $post_views );	
		
		$tmplt_data["body"] = "<section class='main'>".$post_controller->getHomePagePosts( $page, $cat, $search )."</section>";
		
		$base_page = new TemplateBinder( "base_page" );
		$full_page = $base_page->bindTemplate( $tmplt_data );
		//$cache->saveUrlContentToCache( $full_page );
		echo $full_page;
		
	}else{
	
		echo $cache->pullUrlContentFromCache();
		
	}*/
	
	//TEST TO GET TIMESTAMP OF ONE POST AND GET THE NEXT POST BACK IN TIME
	//$time = 1427140819000/1000;	
	$d = new MongoDate(time());
	//echo time();
	var_dump($d);
?>