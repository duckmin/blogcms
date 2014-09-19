<?php
	class TemplateBinder {
       
        public $template_name;
       
        public function __construct( $template_name ){
            $this->template_name = $template_name;
        }
       
        private function getTemplate(){
            $tmplt = dirname(__FILE__)."/../templates/".$this->template_name.".txt";
			if( file_exists( $tmplt ) ){
				return file_get_contents( $tmplt );
			}else{
				return false;
			}
        }
		
		public function bindTemplate( $data ){
            $tmplt = $this->getTemplate();
			if( $tmplt ){
				$binded = preg_replace_callback( "/{{\s*([A-z_]+)\s*}}/", function( $m ) use( $data ){
					$key = strtolower( $m[1] );
					if( array_key_exists( $key, $data ) ){
						return $data[$key];
					}else{
						return "( '".$key."' NOT_SUPPLIED )";
					}
				}, $tmplt );
			   
				return $binded;
			}else{
				return "Template ".$this->template_name." Not Found";
			}
        }
   
    }
   
   /* $str = "This is my {{name  }} and I am {{age }} years old and my state is {{ state }} county {{ county  }}";
    $test = array( "name"=>"Robert", "age"=>"22", "state"=>"VA", "county"=>"fairfax" );
    $binder = new TemplateBinder( $str, $test );
    $tmplt = $binder->bindTemplate();
    echo $tmplt;*/
	
	//$binder = new TemplateBinder( "base_page" );
	//$t=$binder->getTemplate();
	//echo $t;
	
?>