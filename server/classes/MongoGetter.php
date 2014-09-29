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
		
		// connect
			//$m = new MongoClient();

			// select a database
			//$db = $m->blog;

			// select a collection (analogous to a relational database's table)
			//$collection = $db->posts;

			// add a rec	ord
			
			//$collection->insert($document);
				
			
		public function getHomePagePostsFromDb( $page_num ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find()->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );
			return $cursor;
		}
		
		public function getHomePagePostsFromDbByCategory( $page_num, $cat ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$collection = $this->db->posts;			
			$cursor = $collection->find( array( "category"=>$cat ) )->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );
			return $cursor;
		}
		
		public function getBlogManagePosts( $page_num ){  //for manage get_post_info.php
		
			$count = ( $page_num-1 )*$GLOBALS['amount_on_manger_tab'];
			$skip = $GLOBALS['amount_on_manger_tab']+1;
			$collection = $this->db->posts;	
			$fields = array( "_id_"=>true, "category"=>true, "title"=>true, "description"=>true );				
			$cursor = $collection->find( array(), $fields )->limit($skip)->skip($count)->sort( array( '_id' => -1 ) );
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
		/*
		public function getHomePagePostsFromDbByCategory( $page_num, $cat ){
		
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip=$GLOBALS['amount_on_main_page']+1;
			$stmt = $this->db_conn->prepare(
				"SELECT 
					id,
					title,
					folder_path,
					tags,
					created,
					category
				FROM 
					posts
				WHERE
					category=:cat
				ORDER BY 
					created  
				DESC 
				LIMIT 
					:count, $skip"
			);
			$stmt->bindParam(':count', $count, PDO::PARAM_INT);
			$stmt->bindParam(':cat', $cat, PDO::PARAM_STR);
			$stmt->execute();
			$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $posts;
		}
		
		public function getSinglePostRowFromDb( $id ){

			$stmt = $this->db_conn->prepare(
				"SELECT 
					*
				FROM 
					posts 
				WHERE
					id = :id"
			);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$posts = $stmt->fetch(PDO::FETCH_ASSOC);
			return $posts;
		}
		
		public function getBlogManagePosts( $page_num ){  //for manage get_post_info.php
		
			$count = ( $page_num-1 )*$GLOBALS['amount_on_manger_tab'];
			$skip = $GLOBALS['amount_on_manger_tab']+1;
			$stmt = $this->db_conn->prepare(
				"SELECT 
					*
				FROM 
					posts 
				ORDER BY 
					created 
				DESC 
				LIMIT 
					:count, $skip"
			);
			$stmt->bindParam(':count', $count, PDO::PARAM_INT);
			$stmt->execute();
			$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $posts;
		}
		
		public function deletePost( $id, $path ){
			$file = $GLOBALS['index_path'].$path."/".$id.".txt";
			if( file_exists( $file ) ){
				$stmt = $this->db_conn->prepare(
					"DELETE FROM
						posts
					WHERE
						id=:id"
				);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->execute();
				unlink ( $file  );
				return true;
			}else{
				return false;
			}
		}*/
		
			
	};
	
?>