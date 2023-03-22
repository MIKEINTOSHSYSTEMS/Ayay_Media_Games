<?php

if (session_status() == PHP_SESSION_NONE) {
	session_start();
}

$login_user = null;

$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if($username){
	$login_user = User::getByUsername($username);

	if($login_user->role === 'admin'){
		define( 'USER_ADMIN', true );
	} else {
		define( 'USER_ADMIN', false );
	}
}

?>