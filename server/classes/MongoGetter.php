<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class MongoGetter
	{
		private $mongo_conn;
		
		public function __construct( $mongo_conn )
		{
			$this->mongo_conn = $mongo_conn;
			$this->db = $this->mongo_conn->blog;
		}
		
		/*public function getHomePagePostsFromDb( $page_num ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find()->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );
			return $cursor;
		}*/
		
		public function getHomePagePostsFromDbByCategory( $page_num, $cat ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => -1 ) );		
			return $cursor;
		}
		
		public function getHomePagePostsFromDbByCategoryAndSearch( $page_num, $cat, $search ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat, '$text'=>array( '$search'=>$search ) ) )->limit($skip)->skip($count)->sort( array( 'lastModified' => -1 ) );
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
			$fields = array( "_id_"=>true, "category"=>true, "title"=>true, "description"=>true );				
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
			$fields = array( '$currentDate'=> array( "lastModified"=>true ) ); //updates timestamp to current date
			$cursor = $collection->update( array( "_id"=>$mongo_id ), $fields );
			return $cursor;
		}
		
		public function getSingleRowById( $id ){
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;	
			$cursor = $collection->findOne( array( "_id"=>$mongo_id ) );
			return $cursor;
		}
		
		public function removeSingleRowById( $id ){
			$mongo_id = new MongoId( $id );
			$collection = $this->db->posts;	
			$cursor = $collection->remove( array( "_id"=>$mongo_id ) );
			return $cursor;
		}
			
	};
	
?>