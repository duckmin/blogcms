<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class MongoGetter
	{
		private $mongo_conn;
		
		public function __construct( $mongo_conn )
		{
			$this->mongo_conn = $mongo_conn;
			$this->db = $this->mongo_conn->$GLOBALS['mongo_db_name'];
		}
		
		/* NOT USED kept for reference
		public function getHomePagePostsFromDb( $page_num ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find()->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );
			return $cursor;
		}*/
		
		/* NOT used ATM kept for reference
		public function getShortendHomePagePostsFromDbByCategory( $page_num, $cat ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat ), array( "post_data"=>array('$slice'=>array(0,1)) ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => -1 ) );		
			return $cursor;
		}*/
		
		public function getHomePagePostsFromDbByCategory( $page_num, $cat, $reverse_sort ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$sort = ($reverse_sort)? 1 : -1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => $sort ) );		
			return $cursor;
		}
		
		public function getHomePagePostsFromDbByCategoryAndSearch( $page_num, $cat, $search, $reverse_sort ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$sort = ($reverse_sort)? 1 : -1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat, '$text'=>array( '$search'=>$search ) ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => $sort ) );
			return $cursor;
		}
		
		public function getPostsFromDbBySearch( $page_num, $search ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_manger_tab'];
			$skip = $GLOBALS['amount_on_manger_tab']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( '$text'=>array( '$search'=>$search ) ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => -1 ) );
			return $cursor;
		}
		
		public function getBlogManagePosts( $page_num, $cat ){  //for manage get_post_info.php
		
			$count = ( $page_num-1 )*$GLOBALS['amount_on_manger_tab'];
			$skip = $GLOBALS['amount_on_manger_tab']+1;
			$filter = array();
			if( strlen( $cat ) > 0 ){
				$filter["category"] = $cat;	
			}
			$collection = $this->db->posts;	
			$fields = array( "_id"=>true, "post_data"=>true, "category"=>true, "title"=>true, "description"=>true, "lastModified"=>true, "author"=>true );				
			$cursor = $collection->find( $filter, $fields )->limit($skip)->skip($count)->sort( array( 'lastModified' => -1 ) );
			return $cursor;
		}
		
		public function getSinglePostDataById( $id ){ 
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;	
			$fields = array( "post_data"=>true );
			$cursor = $collection->findOne( array( "_id"=>$mongo_id ), $fields );
			return $cursor;
		}
		
		public function updateSinglePostDataById( $id, $post_data ){ 
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;				
			$fields = array( '$set'=> array( "post_data"=>$post_data ) );
			$cursor = $collection->update( array( "_id"=>$mongo_id ), $fields );
			return $cursor;
		}
		
		public function renewPostDate( $id ){ 
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;				
			//updates lastModified to current date
			$fields = array( '$set'=>array( "lastModified"=> new MongoDate() ) );			
			$cursor = $collection->update( array( "_id"=>$mongo_id ), $fields );
			return $cursor;
		}
		
		public function getSingleRowById( $id ){
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;	
			$cursor = $collection->findOne( array( "_id"=>$mongo_id ) );
			return $cursor;
		}
		
		public function getSingleRowFromDate( $title, $start, $end ){
			$title_uncode = urldecode( $title );			
			$start_d = new MongoDate( $start );
			$end_d = new MongoDate( $end );
			$collection = $this->db->posts;	
			$cursor = $collection->findOne( array( "title"=>$title_uncode, "lastModified"=>array( '$gte'=>$start_d, '$lte'=>$end_d ) ) );
			return $cursor;
		}
		
		//query used on post page to get the next post by timestamp and create a link to it at the bottom
		public function getPreviousPostsFromTimestamp( $cat, $time_stamp ){
			$mongo_date = new MongoDate( $time_stamp );
			$collection = $this->db->posts;
			$fields = array( "_id"=>true, "title"=>true, "lastModified"=>true );					
			$cursor = $collection->find( array( "category"=>$cat, "lastModified"=>array( '$lt'=>$mongo_date ) ), $fields )->limit(1)->sort( array( 'lastModified' => -1 ) );
			return $cursor;
		}
		
		public function removeSingleRowById( $id ){
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;	
			$cursor = $collection->remove( array( "_id"=>$mongo_id ) );
			return $cursor;
		}
		
		public function getUniqueAnalyticUrlPage(){
			$collection = $this->db->analytics;			
			$cursor = $collection->distinct('url');		
			return $cursor;
		}
		
		public function getPageCountsByUrlAndDateRange( $url, $start, $end ){
			$start_date = new MongoDate( $start );
			$end_date = new MongoDate( $end );
			$date_array = array( '$gte'=>$start_date, '$lte'=>$end_date );
			$collection = $this->db->analytics;			
			$cursor = $collection->find( array( 'url'=>$url, 'date'=>$date_array ) )->sort( array( 'date'=>1 ) );	
			return $cursor;
		}
			
	};
	
?>