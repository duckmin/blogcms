<?php
	include_once dirname(__FILE__)."/../configs.php";
	
    class CacheController {
       
        private $cache_dir;
        private $today;
        private $todays_dir;
        private $yesterdays_dir;
        private $url;
        private $cache_file_path;
       
        public function __construct( $cache_dir, $url ){
            $this->cache_dir = $cache_dir;
            $this->url = urlencode( $url );
            $this->today = date("m-d-y");
            $this->yesterday = date("m-d-y", time() - 60 * 60 * 24);
            $this->todays_dir = $this->cache_dir."/".$this->today;
            $this->yesterdays_dir = $this->cache_dir."/". $this->yesterday;
            $this->cache_file_path = $this->todays_dir."/".$this->url.".txt";
        }
       
        public function urlInCache(){
            $incache = false; //default
            if( is_dir( $this->todays_dir ) ){
                if( file_exists( $this->cache_file_path ) ){
                    $incache = true;
                }
            }
            return $incache;
        }
       
        public function saveUrlContentToCache( $content ){
            if( !$this->urlInCache() ){
                //if todays dir dosnt exist create it
                if( !is_dir( $this->todays_dir ) ){
                    mkdir( $this->todays_dir, 0777 );
                   
                    //remove yesterdays cache dir we will not use yesterdays dir again
                    if( is_dir( $this->yesterdays_dir ) ){
                        $this->removeYesterdaysCacheDir();
                    }
                }
                if( !file_exists( $this->cache_file_path ) ){
                    $cachefile = fopen( $this->cache_file_path, "w" );
                    fwrite( $cachefile, $content  );
                    fclose( $cachefile );
                    return true;
                }
            }else{
                trigger_error("File Is Already In Cache", E_USER_ERROR); //cant save new file if already exists
            }
        }
       
        public function pullUrlContentFromCache(){
            if( $this->urlInCache() ){
                if( file_exists( $this->cache_file_path ) ){
                    return file_get_contents( $this->cache_file_path );
                }
            }else{
                trigger_error("File Not In Cache", E_USER_ERROR); //cant get file contents if it dosnt exist
            }
        }
       
        private function removeYesterdaysCacheDir(){
            exec( 'rm -rf '.$this->yesterdays_dir);
        }
		
		public function clearCache(){
            if( is_dir( $this->cache_dir ) ){
                $scan = scandir( $this->cache_dir );
                for( $i = 0; $i < count( $scan ); $i++ ){
                    $current = $scan[$i];
                    if( $current !== "." &&  $current !== ".." ){
                        $path = $this->cache_dir."/".$current;
                        if( is_dir( $path ) ){
                            exec( 'rm -rf '.$path );
                        }
                    }
                }
            }
        }
   
    }
   
   
    //run this to make to do a check of the cache system
    //will throw fatal error if the file already exists! ( when running for a second time with same url id )
    /*$cache_dir = dirname(__FILE__).'/cache_test';
    $cache_url = "http://www.allthingsrobert.net/index.php?p=5&s=whussup";
    $cachecontroller = new CacheController( $cache_dir, $cache_url );
   
    $in_cache = $cachecontroller->urlInCache();
    echo var_dump( $in_cache )." before save in cache check<br>";
   
    $content = "I am the content of the URL ".$cache_url;
    $saved = $cachecontroller->saveUrlContentToCache( $content );
    echo var_dump( $saved )." was saved<br>";
   
    $in_cache2 = $cachecontroller->urlInCache();
    echo var_dump( $in_cache2 )." after saved in cache check<br>";
   
    $file_content = $cachecontroller->pullUrlContentFromCache();
    echo "content of the file-----<br>".$file_content;*/
	
?>