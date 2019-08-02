<?php


if ( ! defined( 'ABSPATH' ) ) { exit; };


/**

 Всё собирает и запускает

*/



class pstuMisspellingsManager {



    protected $domain;



    protected $path;



    function __construct ( $slug, $version, $domain ) {
        $this->domain = $domain;
        require_once PSTU_MISSPELLINGS_INCLUDES . 'abstract-path.php';
    	if ( is_admin() ) {
    		if ( wp_doing_ajax() ) {
    			require_once PSTU_MISSPELLINGS_INCLUDES . 'class-ajax.php';
                $this->path = new pstuMisspellingsAjax( $slug, $version, $domain );
    		} else {
    			require_once PSTU_MISSPELLINGS_INCLUDES . 'class-admin.php';
                $this->path = new pstuMisspellingsAdmin( $slug, $version, $domain );
    		}
    	} else {
    		require_once PSTU_MISSPELLINGS_INCLUDES . 'class-public.php';
            $this->path = new pstuMisspellingsPublic( $slug, $version, $domain );
    	}
    }


    public function run () {
        add_action ( 'plugins_loaded', array( $this, 'textdomain' ) );
    	$this->path->run();
    }



    public function textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            PSTU_MISSPELLINGS_LANGUAGES
        );
    }



}