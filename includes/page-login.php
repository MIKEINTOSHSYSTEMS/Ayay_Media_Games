<?php

if(is_login()){
	$user_data = get_user($_POST['username']);
	if($user_data['role'] === 'admin'){
		header('Location: '.DOMAIN.'admin/dashboard.php');
		return;
	} else {
		header('Location: '.get_permalink('user', $_SESSION['username']));
		return;
	}
}

$errors = array();

if ( isset( $_POST['login'] ) ) {
	$user_data = get_user($_POST['username']);
	if($user_data){
		if(password_verify($_POST['password'], $user_data['password'])){
			$_SESSION['username'] = $_POST['username'];

			if($user_data['role'] === 'admin'){
				header('Location: '.DOMAIN.'admin/dashboard.php');
				update_login_history('success');
				return;
			} else {
				header('Location: '.get_permalink('user', $_SESSION['username']));
				return;
			}
		}
	}
	$errors[] = 'Incorrect username or password.';
}

if (isset($_POST['login'])) {
	$timer            = time() - 30;
	$ip_address      = getIpAddr();
	// Getting total count of hits on the basis of IP
	$conn = open_connection();
	$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM loginlogs WHERE TryTime > :timer and IpAddress = :ip_address";
	$st = $conn->prepare($sql);
	$st->bindValue(":timer", $timer, PDO::PARAM_INT);
	$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
	$st->execute();
	$sql = "SELECT FOUND_ROWS() AS total_count";
	$totalRows = $conn->query($sql)->fetch();
	$total_count     = $totalRows[0];
	if ($total_count == 10) {
		$errors[] = _t('To many failed login attempts. Please login after 30 sec.');
	} else {
		$total_count++;
		$rem_attm = 10 - $total_count;
		if ($rem_attm == 0) {
			$errors[] = _t('To many failed login attempts. Please login after 30 sec.');
		} else {
			$errors[] = _t('%a attempts remaining.', $rem_attm);
		}
		$try_time = time();;
		$sql = "INSERT INTO loginlogs(IpAddress,TryTime) VALUES(:ip_address, :try_time)";
		$st = $conn->prepare($sql);
		$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
		$st->bindValue(":try_time", $try_time, PDO::PARAM_INT);
		$st->execute();
	}
}

function update_login_history($status = 'null'){
	$ip_address = getIpAddr();
	$data = array(
		'username' => $_POST['username'],
		'password' => '***',
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
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Player Login | <?php echo SITE_TITLE ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN ?>admin/style/bootstrap.min.css">
		<!-- Material Design Bootstrap -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo DOMAIN ?>admin/style/admin.css">
		<!-- Font Awesome icons (free version)-->
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
		<!-- MDB core JavaScript -->
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
	</head>
	<body class="login-body">
		<div class="login-container">
			<div class="login-form">
				<div class="container">
					<div class="login-logo text-center">
						<img src="../images/ayay.png">
					</div>
					<form action="admin.php?action=login" method="POST">
						<?php
						if(count($errors) > 0){
							foreach ($errors as $msg) {
								echo '<div class="alert alert-warning" role="alert">'.$msg.'</div>';
							}
						}
						?>
						<input type="hidden" name="login" value="true" />
						<div class="form-group">
							<input type="text" id="username" name="username" placeholder="Username" class="form-control" value="" required>
						</div>
						<div class="form-group">
							<input type="password" id="password" name="password" placeholder="Password" class="form-control" value="" type="password" required>
						</div>
						<button type="submit" class="btn btn-info btn-block"><?php _e('Login') ?></button>
						<?php if($options['user_register'] === 'true'){ ?>
							<br>
							<div class="text-center"><?php _e('Or') ?> <a href="<?php echo get_permalink('register') ?>"><?php _e('Register') ?></a></div>
						<?php } ?>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>