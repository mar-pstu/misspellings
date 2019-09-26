<?php





if ( ! defined( 'ABSPATH' ) ) {	exit; };





class pstuMisspellingsAjax extends pstuMisspellingsAbstractPath {



	protected $fields;



	public function run() {
		add_action( "wp_ajax_{$this->slug}", array( $this, 'manager' ) );
	   	add_action( "wp_ajax_nopriv_{$this->slug}", array( $this, 'manager' ) );
	}


	protected function check_secure() {
		if ( ! check_ajax_referer( $this->slug, 'security' ) ) {
			wp_send_json_error();
		}
	}

 
	protected function set_fields( $query ) {
		if ( is_user_logged_in() ) {
 			$current_user = wp_get_current_user();
 			$query[ 'name' ] = $current_user->display_name;
 			$query[ 'email' ] = $current_user->user_email;
 		}
		foreach ( array( 'login', 'post', 'name', 'email', 'error', 'message' ) as $key ) {
			if ( isset( $query[ $key ] ) ) {
				switch ( $key ) {
					case 'login':
						if ( ! empty( $query[ $key ] ) ) wp_send_json_error( 'login' );
						break;
					case 'error':
					case 'message':
						$this->fields[ $key ] = sanitize_text_field( $query[ $key ] );
						break;
					case 'post':
						$this->fields[ $key ] = sanitize_key( $query[ $key ] );
						break;
					case 'name':
						$this->fields[ $key ] = ( empty( trim( $query[ $key ] ) ) ) ? 'Anonymous' : sanitize_text_field( $query[ $key ] );
						break;
					case 'email':
						$this->fields[ $key ] = sanitize_email( $query[ $key ] );
						break;
					default:
						$this->fields[ $key ] = sanitize_text_field( $query[ $key ] );
						break;
				}
			} else {
				$this->fields[ $key ] = '';
			}
		}
	}



	protected function get_message() {
		$result = __return_empty_string();
		$blog_name = get_bloginfo( 'name' );
		ob_start();
		include PSTU_MISSPELLINGS_VIEWS . 'admin-message.php';
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}



	protected function send_mail() {
		$options = get_option( $this->slug );
		$emails = ( empty( $options[ 'emails' ] ) ) ? get_bloginfo( 'admin_email' ) : $options[ 'emails' ];
		$subject = sprintf(
			'%1$s %2$s',
			__( 'Сообщение с сайта', $this->domain ),
			get_bloginfo( 'name' )
		);
		$headers = sprintf(
			'From: %1$s <%2$s>%3$sContent-type: text/html%3$scharset=utf-8%3$s',
			$this->fields[ 'name' ],
			( is_email( $this->fields[ 'email' ] ) ) ? $this->fields[ 'email' ] : get_bloginfo( 'admin_email' ),
			"\r\n"
		);
		$sendmail_result = wp_mail(
			$emails,
			$subject,
			$this->get_message(),
			$headers
		);
		if ( $sendmail_result ) {
			wp_send_json_success();
		} else {
			wp_send_json_error( __( 'Попробуйте позже или обратитесь к администратору.', $this->domain ) );
		}
	}



	public function manager() {

		$this->check_secure();

		if ( isset( $_POST[ 'query' ] ) ) {
			$this->set_fields( $_POST[ 'query' ] );
		} else {
			wp_send_json_error();
		}

		if ( empty( $this->fields[ 'message' ] ) && empty( $this->fields[ 'error' ] ) ) {
			wp_send_json_error( __( 'Выделите текст или напишите сообщение.', $this->domain ) );
		}
		
		$blacklist_check_result = wp_blacklist_check( 
			$this->fields[ 'name' ],
			$this->fields[ 'email' ],
			'',
			$this->fields[ 'message' ],
			$this->get_the_user_ip(),
			''
		);

		if ( $blacklist_check_result ) {
			wp_send_json_error( __( 'Вы в "чёрном списке". Обратитесь к администратору сайта.', $this->domain ) );
		} else {
			$this->send_mail();
		}

		wp_die();

	}



	/**
	 *	Получение IP пользователя
	 */
	private function get_the_user_ip() {
		if ( ! empty( $_SERVER[ 'HTTP_CLIENT_IP' ] ) ) {
			$ip = $_SERVER[ 'HTTP_CLIENT_IP' ];
		} elseif ( ! empty( $_SERVER[ 'HTTP_X_FORWARDED_FOR' ] ) ) {
			$ip = $_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
		} else {
			$ip = $_SERVER[ 'REMOTE_ADDR' ];
		}
		return apply_filters( 'edd_get_ip', $ip );
	}


}