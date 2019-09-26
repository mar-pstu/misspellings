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
        } elseif ( ! $this->is_rest() ) {
            require_once PSTU_MISSPELLINGS_INCLUDES . 'class-public.php';
            $this->path = new pstuMisspellingsPublic( $slug, $version, $domain );
        }
    }


    public function run () {
        if ( isset( $this->path ) ) {
            add_action ( 'plugins_loaded', array( $this, 'textdomain' ) );
            $this->path->run();
        }
    }



    public function textdomain() {
        load_plugin_textdomain(
            $this->domain,
            false,
            PSTU_MISSPELLINGS_LANGUAGES
        );
    }



    function is_rest() {
        $prefix = rest_get_url_prefix();
        if (
            defined( 'REST_REQUEST' )
            && REST_REQUEST // (#1)
            || isset( $_GET['rest_route'] ) // (#2)
            && strpos( trim( $_GET[ 'rest_route' ], '\\/' ), $prefix , 0 ) === 0
        ) return true;
        $rest_url = wp_parse_url( site_url( $prefix ) );
        $current_url = wp_parse_url( add_query_arg( array( ) ) );
        return strpos( $current_url[ 'path' ], $rest_url[ 'path' ], 0 ) === 0;
    }



}