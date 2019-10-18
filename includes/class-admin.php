<?php




if ( ! defined( 'ABSPATH' ) ) {	exit; };





class pstuMisspellingsAdmin extends pstuMisspellingsAbstractPath {


	/**
	 *
	 *
	 */
	function run() {
		add_action ( 'admin_menu', array( $this, 'page' ) );
		add_action ( 'admin_init', array( $this, 'settings' ) );
	}




	/**
	 * Регмстрация страницы настроек
	 *
	 */
	public function page () {
		add_options_page(
			__( 'ПГТУ "Очепятки"', $this->domain ),
			__( 'ПГТУ "Очепятки"', $this->domain ),
			'manage_options',
			$this->slug,
			array( $this, 'show' )
		);
	}




	/**
	 * Регистрация настроек
	 *
	 */
	public function settings() {
		register_setting(
			$this->slug,
			$this->slug,
			array( $this, 'validate' )
		);
		/**/
		add_settings_section(
			'form_settings',
			__( 'Параметри форми', $this->domain ),
			'',
			$this->slug
		);
		/**/
		add_settings_field(
			'emails',
			__( 'Email для оповещения', $this->domain ),
			array( $this, 'get_control' ),
			$this->slug,
			'form_settings',
			 array(
				'type'         => 'text',
				'desc'         => __( 'список адресов email через запятую или пробел', $this->domain ),
				'placeholder'  => 'example@pstu.edu, example@gmail.com, admin@pstu.edu',
				'id'           => 'emails',
			)
		);
	}




	/**
	 * Возвращает поле формы
	 *
	 * @param    array     $params    Параметры поля формы
	 * @return   string    $result    html
	 */
	public function get_control( $args ) {
		extract( $args );
		$options = get_option( $this->slug );
		switch ( $type ) {
			case 'text':
				printf(
					'<input type="text" name="%1$s[%2$s]" value="%3$s" placeholder="%4$s" class="regular-text" id="%1$s_%2$s" /> %5$s',
					$this->slug,
					$id,
					( isset( $options[ $id ] ) && $options[ $id ] ) ? esc_attr( $options[ $id ] ) : '',
					( empty( $placeholder ) ) ? '' : strip_tags( $placeholder ),
					( empty( $desc ) ) ? '' : '<p class="description">' . $desc . '</p>'
				);
				break;
		}
	}




	/**
	 * Валидация настроек
	 *
	 * @param    array     $options    массив опций формы
	 * @return   array     $result     $id => $value
	 */
	public function validate( $options ) {
		$result = __return_empty_array();
		foreach ( $options as $key => $value ) {
			switch ( $key ) {
				case 'emails':
					$result[ $key ] = implode( ", ", array_filter( wp_parse_list( sanitize_text_field( $value ) ), 'is_email' ) );
					break;
				default:
					$result[ $key ] = sanitize_text_field( $value );
					break;
			}
		}
		return $result;
	}




	/**
	 * Вывод страницы настроек на экран
	 *
	 */
	public function show() {
		include PSTU_MISSPELLINGS_VIEWS . 'settings-page.php';
	}




}