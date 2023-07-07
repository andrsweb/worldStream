<?php

/**
 * Clean incoming value from trash.
 *
 * @param	mixed	$value	Some value to clean.
 * @return	mixed	$value	The same value, but cleaned.
 */
function as_clean_value( $value )
{
	$value = trim( $value );
	$value = stripslashes( $value );
	$value = strip_tags( $value );

	return htmlspecialchars( $value );
}

/**
 * Function checks if value length is between min and max parameters.
 *
 * @param   string	$value  Specific string..
 * @param   int		$min    Minimum symbols value length.
 * @param   int		$max	Maximum symbols value length.
 * @return  bool            True if OK, false if value length is too small or large.
 */
function as_check_length( string $value, int $min, int $max ): bool
{
	return ! ( mb_strlen( $value ) < $min || mb_strlen( $value ) > $max );
}

/**
 * Function checks name symbols.
 *
 * @param   string  $name   Some name.
 * @return  bool            True if OK, false if string has bad symbols.
 */
function as_check_name( string $name ): bool
{
	return preg_match('/^[a-zа-я\s]+$/iu', $name );
}

/**
 * Function checks phone symbols.
 *
 * @param   string  $phone  Some phone number.
 * @return  bool            True if OK, false if string has bad symbols.
 */
function as_check_phone( string $phone ): bool
{
	return preg_match('/^[0-9()+\-\s]+$/iu', $phone );
}

if( ! empty( $_POST ) && isset( $_POST['func'] ) ){
	switch( $_POST['func'] ){
		case 'top-form':
			as_send_top_form();
			break;

		case 'bottom-form':
			as_send_bottom_form();
			break;

		default:
			as_send_bottom_form();
	}
}

// Feedback.
function as_send_top_form(){
	$name		= isset( $_POST['top-name'] ) ? as_clean_value( $_POST['top-name'] ) : null;
	$tel		= isset( $_POST['top-tel'] ) ? as_clean_value( $_POST['top-tel'] ) : null;
	$title		= 'Быстрая заявка';

	// Required fields.
	if( ! $name || ! $tel ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Пожалуйста, заполните все поля.'
		] );
		die();
	}

	// Only letters & spaces in name.
	if( ! as_check_name( $name ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Пожалуйста, введите корректное имя.'
		] );
		die();
	}

	// Check length to avoid very large text.
	if( ! as_check_length( $name, 1, 50 ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Имя не должно превышать 50 символов.'
		] );
		die();
	}

	if( ! as_check_length( $tel, 3, 30 ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Телефон не должен превышать 30 символов или быть меньше 3 символов.'
		] );
		die();
	}

	// Check phone symbols.
	if( ! as_check_phone( $tel ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Пожалуйста, введите корректный телефон.'
		] );
		die();
	}

	// Prepare message for mail.
	$message = "Привет!\n" .
		"{$title}:\n\n" .
		"Имя - $name\n" .
		"Телефон - $tel \n\n\n";

	as_send_email( $title, $message );
}

// Promo.
function as_send_bottom_form(){
	$name	= isset( $_POST['name'] ) ? as_clean_value( $_POST['name'] ) : null;
	$email	= isset( $_POST['email'] ) ? as_clean_value( $_POST['email'] ) : null;
	$tel	= isset( $_POST['tel'] ) ? as_clean_value( $_POST['tel'] ) : null;
	$town	= isset( $_POST['town'] ) ? as_clean_value( $_POST['town'] ) : null;
	$mess	= isset( $_POST['message'] ) ? as_clean_value( $_POST['message'] ) : null;
	$title	= 'Основная форма';

	// Required fields.
	if( ! $email || ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Пожалуйста, заполните все поля.'
		] );
		die();
	}

	if( ! as_check_length( $email, 5, 50 ) ){
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Email не должен превышать 50 символов или быть меньше 5 символов.'
		] );
		die();
	}

	// Prepare message for mail.
	$message = "Привет!\n" .
		"{$title}:\n\n" .
		"Имя - $name\n" .
		"Почта - $email\n" .
		"Город - $town\n" .
		"Телефон - $tel \n" .
		"Сообщение - $mess \n\n\n";
		
	as_send_email( $title, $message );
}

/**
 * @param string $subject
 * @param string $message
 * @return void
 */
function as_send_email( string $subject, string $message ){
	// Mail headers.
	$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" .
		"Reply-To: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" .
		"X-Mailer: PHP/" . phpversion();

	$result = mail('Your email', $subject, $message, $headers );

	if( $result )
		echo json_encode( [
			'success'	=> 1,
			'message'	=> 'Спасибо за Ваше сообщение! Мы свяжемся с Вами в ближайшее время.'
		] );	// Success.
	else
		echo json_encode( [
			'success'	=> 0,
			'message'	=> 'Ошибка отправки. Пожалуйста, попробуйте позже.'
		] );	// Failed.
}

die();
