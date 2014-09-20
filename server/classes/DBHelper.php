<?php
	include_once dirname(__FILE__)."/../configs.php";
	
	/*function isLoggedIn(){
		$logged_in=false;
		if( isset( $_SESSION['logged_in'] ) && $_SESSION['logged_in']===true ){
			$logged_in=true;
		}
		return $logged_in;
	};*/

	class DBHelper
	{
		static public function dbConnection(){
			$conn = new PDO('mysql:host='.$GLOBALS['db_server'].';dbname=blog', $GLOBALS['db_user'], $GLOBALS['db_pw']);
			//$conn = new PDO('mysql:host=205.178.146.110;dbname=php_blog', 'rogordon', 'Weezer25');
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		}
		
		static function inserter( $table, $obj, $db_conn ){
			$values="";
			$columns="";
			foreach( $obj as $column_name => $value ){
				$columns.=$column_name.",";
				$values.=":".$column_name.",";
			}
			$cols=substr( $columns, 0, strlen( $columns )-1 );
			$vals=substr( $values, 0, strlen( $values )-1 );
			$query="INSERT INTO ".$table." (
				".$cols."
			)
			VALUES 
				( ".$vals." )";

			$stmt = $db_conn->prepare( $query );
			foreach( $obj as $column_name => &$value ){
				
				$bind=":".$column_name;
				if( is_numeric( $value ) ){
					$stmt->bindParam( $bind, $value, PDO::PARAM_INT );
				}else{
					$stmt->bindParam( $bind, $value, PDO::PARAM_STR );
				}
			}
			$stmt->execute();
		}
		/*"UPDATE 
						form.replies
					SET
						text=:new_text
					WHERE
						replies.id=:rid"*/
		static function upDateByPrimaryKey( $table, $primary_key, $obj, $db_conn ){
			$values="";
			$columns="";
			foreach( $obj as $column_name => $value ){
				if( $column_name!==$primary_key ){
					//$columns.=$column_name.",";
					$values.=$column_name."=:".$column_name.",";
				}
			}
			
			//$cols=substr( $columns, 0, strlen( $columns )-1 );
			$vals=substr( $values, 0, strlen( $values )-1 );
			$query="UPDATE ".$table." 			

			SET
				".$vals."
			WHERE
				".$primary_key."=:".$primary_key;
			
			//echo $query;
			$stmt = $db_conn->prepare( $query );
			
			foreach( $obj as $column_name => &$value ){
				
				$bind=":".$column_name;
				if( is_numeric( $value ) ){
					$stmt->bindParam( $bind, $value, PDO::PARAM_INT );
				}else{
					$stmt->bindParam( $bind, $value, PDO::PARAM_STR );
				}
			}
			$stmt->execute();
		}
	}

?>
