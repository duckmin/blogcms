<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	class DbGetter
	{
		private $db_conn;
		
		public function __construct( $db_conn )
		{
			$this->db_conn = $db_conn;
		}
		
		public function getHomePagePostsFromDb( $page_num ){
			$count = ( $page_num-1 )*$GLOBALS['amount_on_main_page'];
			$skip = $GLOBALS['amount_on_main_page']+1;
			$stmt = $this->db_conn->prepare(
				"SELECT 
					id,
					title,
					folder_path,
					tags,
					created 
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
		}
		
			
	};
	
?>