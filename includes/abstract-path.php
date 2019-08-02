<?php






if ( ! defined( 'ABSPATH' ) ) {	exit; };





abstract class pstuMisspellingsAbstractPath {



	protected $slug;



 	protected $version;



 	protected $domain;



 	function __construct( $slug, $version, $domain ) {
 		$this->slug = $slug;
 		$this->version = $version;
 		$this->domain = $domain;
 	}


 	public function run() {
		
	}


}