<?php if ( ! defined( 'ABSPATH' ) ) { exit; }; ?>


<h1><?php _e( 'Оповещение об ошибке на сайте', $this->domain ); ?> <?php echo $blog_name; ?></h1>
<h2><?php _e( 'Информация об ошибке', $this->domain ); ?></h2>
<table cellspacing="0" style="border: 1px solid #bbbbbb; width: 100%;">
	<?php if ( ! empty( $this->fields[ 'post' ] ) ) : ?>
		<tr>
			<td style="border: 1px solid #bbbbbb; padding: 5px; width: 20%;"><b><?php _e( 'Страница с ошибкой', $this->domain ); ?></b></td>
			<td style="border: 1px solid #bbbbbb; padding: 5px;">
				<a href="<?php echo  get_permalink( $this->fields[ 'post' ] ); ?>">
					<?php echo get_the_title( $this->fields[ 'post' ] ); ?>
				</a>
			</td>
		</tr>
	<?php endif; ?>
	<tr>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><b><?php _e( 'Выделенный текст', $this->domain ); ?></b></td>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><?php echo $this->fields[ 'error' ]; ?></td>
	</tr>
	<tr>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><b><?php _e( 'Описание', $this->domain ); ?></b></td>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><?php echo $this->fields[ 'message' ]; ?></td>
	</tr>
</table>
<h2><?php _e( 'Информация об авторе сообщения', $this->domain ); ?></h2>
<table cellspacing="0" style="border: 1px solid #bbbbbb; width: 100%;">
	<tr>
		<td style="border: 1px solid #bbbbbb; padding: 5px; width: 20%;"><b><?php _e( 'IP автора', $this->domain ); ?></b></td>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><?php echo $this->get_the_user_ip(); ?></td>
	</tr>
	<tr>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><b><?php _e( 'Имя автора', $this->domain ); ?></b></td>
		<td style="border: 1px solid #bbbbbb; padding: 5px;"><?php echo $this->fields[ 'name' ] ?></td>
	</tr>
	<?php if ( ! empty( $this->fields[ 'email' ] ) ) : ?>
		<tr>
			<td style="border: 1px solid #bbbbbb; padding: 5px;"><b><?php _e( 'Email автора', $this->domain ); ?></b></td>
			<td style="border: 1px solid #bbbbbb; padding: 5px;">
				<a href="mailto:<?php echo $this->fields[ 'email' ] ?>">
					<?php echo $this->fields[ 'email' ]; ?>
				</a>
			</td>
		</tr>
	<?php endif; ?>
</table>