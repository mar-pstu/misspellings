<?php






if ( ! defined( 'ABSPATH' ) ) {	exit; };






 class pstuMisspellingsPublic extends pstuMisspellingsAbstractPath {



 	protected $fields;



 	/**
 	 * Регистрирует события и фильтры
 	 *
 	 */
 	public function run() {
 		add_filter( 'the_content',          array( $this, 'show_message' ),       10, 1 );
 		add_action( 'wp_enqueue_scripts',   array( $this, 'register_enqueue' ),   10, 0 );
	 	add_action( 'wp_footer',            array( $this, 'render_form' ),        10, 0 );
 	}




 	/**
 	 * Регистрирует скрипты и стили
 	 *
 	 */
 	public function register_enqueue() {
 		wp_register_style(
			'fancybox',
 			PSTU_MISSPELLINGS_ASSETS . 'styles/fancybox.css',
 			array(),
 			'3.3.5',
			'all'
		);
 		wp_register_script(
			'fancybox',
			PSTU_MISSPELLINGS_ASSETS . 'scripts/fancybox.js',
			array( 'jquery' ),
			'3.3.5',
			true
		);
		wp_register_style(
			$this->slug,
 			PSTU_MISSPELLINGS_ASSETS . 'styles/public.css',
 			array(),
 			$this->version,
			'all'
		);
 		wp_register_script(
 			$this->slug,
 			PSTU_MISSPELLINGS_ASSETS . 'scripts/public.js',
 			array( 'jquery', 'fancybox' ),
 			$this->version,
 			true
 		);
 		wp_localize_script(
 			$this->slug,
 			'pstuMisspellings',
 			array(
 				'slug'          => $this->slug,
 				'method'        => 'POST',
 				'ajaxurl'       => admin_url( 'admin-ajax.php' ),
 				'security'      => wp_create_nonce( $this->slug ),
				'success'       => __( 'Сообщение отправлено.', $this->domain ),
				'error'         => __( 'Произошла ошибка.', $this->domain ),
				'notice'        => __( 'Если вы заметили ошибку, пожалуйста, выделите фрагмент текста и нажмите Ctrl + Enter или это сообщение.', $this->domain ),
 			)
 		);
 	}



 	/**
 	 * Добавляет контейнер для сообщения к содержимому страницы и добавляет скрипты и стили в очередь на вывод
 	 *
 	 * @param    string    $content    Параметры поля формы
	 * @return   string    $result     html
 	 */
 	function show_message( $content ) {
 		$result = sprintf(
 			'%1$s <a id="%2$s_button" class="%2$s_button" role="button" href="#%2$s_modal" data-post="%3$s"> </a>',
 			$content,
 			$this->slug,
 			get_the_ID()
 		);
 		return $result;
 	}



 	/**
 	 * Возвращает поля формы
 	 */
 	protected function get_form_controls() {
 		$result = __return_empty_array();
 		foreach ( $this->fields as $key => $value ) {
 			switch ( $value[ 'type' ] ) {
				case 'textarea':
					$result[] = sprintf(
						'<textarea name="%1$s_%2$s" placeholder="%3$s" id="%1$s_%2$s" %4$s>%5$s</textarea>',
						$this->slug,
						$key,
						esc_attr( $value[ 'placeholder' ] ),
						readonly( true, $value[ 'readonly' ], false ),
						$value[ 'value' ]
					);
					break;
				case 'hidden':
				case 'email':
				case 'text':
				default:
					$result[] = sprintf(
						'<input type="%6$s" name="%1$s_%2$s" placeholder="%3$s" id="%1$s_%2$s" value="%5$s" %4$s>',
						$this->slug,
						$key,
						esc_attr( $value[ 'placeholder' ] ),
						readonly( true, $value[ 'readonly' ], false ),
						esc_attr( $value[ 'value' ] ),
						$value[ 'type' ]
					);
					break;
			}
 		}
 		return implode( "\r\n", $result );
 	}



 	protected function set_form_fields() {
 		$this->fields = __return_empty_array();
 		if ( ! is_user_logged_in() ) {
 			$this->fields[ 'name' ] = array(
				'type'         => 'text',
				'value'        => '',
				'placeholder'  => __( 'Ваше имя', $this->domain ),
				'readonly'     => false,
			);
			$this->fields[ 'email' ] = array(
				'type'         => 'email',
				'value'        => '',
				'placeholder'  => __( 'Ваш email', $this->domain ),
				'readonly'     => false,
			);
 		}
 		$this->fields[ 'login' ] = array(
			'type'         => 'hidden',
			'value'        => '',
			'placeholder'  => '',
			'readonly'     => false,
		);
 		$this->fields[ 'post' ] = array(
			'type'         => 'hidden',
			'value'        => '',
			'placeholder'  => '',
			'readonly'     => true,
		);
		$this->fields[ 'error' ] = array(
			'type'         => 'textarea',
			'value'        => '',
			'placeholder'  => __( 'Выделенный текст', $this->domain ),
			'readonly'     => true,
		);
		$this->fields[ 'message' ] = array(
			'type'         => 'textarea',
			'value'        => '',
			'placeholder'  => __( 'Сообщение', $this->domain ),
			'readonly'     => false,
 		);
 	}



 	public function render_form() {
 		wp_enqueue_style( 'fancybox' );
 		wp_enqueue_script( 'fancybox' );
 		wp_enqueue_style( $this->slug );
 		wp_enqueue_script( $this->slug );
 		$this->set_form_fields();
 		printf(
 			'<div style="display: none;" class="%1$s_modal" id="%1$s_modal"><form id="%1$s_form" class="%1$s_form"><h3>%4$s</h3>%2$s<input type="submit" value="%3$s"></form></div>',
 			$this->slug,
 			$this->get_form_controls(),
 			esc_attr__( 'Отправить', $this->domain ),
 			__( 'Сообщите об ошибке', $this->domain )
 		);
 	}


 }