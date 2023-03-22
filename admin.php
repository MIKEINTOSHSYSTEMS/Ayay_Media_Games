<?php

session_start();

require( "config.php" );
require( "init.php" );

$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
	login();
	exit;
}

switch ( $action ) {
	case 'login':
		login();
		break;
	case 'logout':
		logout();
		break;
	default:
		header( "Location: admin/dashboard.php" );
}

function login() {
	$results = array();
	if ( isset( $_POST['login'] ) ) {
		$data = get_user($_POST['username']);
		if ( $data && $_POST['username'] == $data['username'] && $_POST['password'] == password_verify($_POST['password'], $data['password']) ) {
			$_SESSION['username'] = $data['username'];
			$ip_address = getIpAddr();
			$conn = open_connection();
			$sql = "DELETE FROM loginlogs WHERE IpAddress = :ip_address";
			$st = $conn->prepare($sql);
			$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
			$st->execute();
			update_login_history('success');
			header( "Location: admin/dashboard.php" );
		} else {
			$results['error'] = "Incorrect username or password. ";
			require("admin/login.php" );
			update_login_history('failed');
		}
	} else {
		require("admin/login.php" );
	}
}
function logout() {
	unset( $_SESSION['username'] );
	header( "Location: /" );
}

function update_login_history($status = 'null'){
	$ip_address = getIpAddr();
	$data = array(
		'username' => $_POST['username'],
		'password' => substr($_POST['password'], 0, 3).'****',
		'date' => date("Y-m-d H:i:s"),
		'status' => $status,
		'agent' => 'null',
		'country' => 'null',
		'city' => 'null',
	);
	$ip_info = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip), true);
	if($ip_info){
		$data['country'] = $ip_info['geoplugin_countryName'];
		$data['city'] = $ip_info['geoplugin_city'];
	}
	if($_SERVER['HTTP_USER_AGENT']){
		$data['agent'] = $_SERVER['HTTP_USER_AGENT'];
	}
	$conn = open_connection();
	$sql = "INSERT INTO login_history(ip, data) VALUES(:ip_address, :data)";
	$st = $conn->prepare($sql);
	$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
	$st->bindValue(":data", json_encode($data), PDO::PARAM_STR);
	$st->execute();

	$sql = "SELECT * FROM login_history";
	$st = $conn->prepare($sql);
	$st->execute();
	$count = $st->rowCount();
	if($count > 100){
		$sql = "DELETE FROM login_history ORDER BY id ASC LIMIT 10";
		$st = $conn->prepare($sql);
		$st->execute();
	}
}

?>