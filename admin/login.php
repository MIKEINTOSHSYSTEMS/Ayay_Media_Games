<?php
	$msg = '';
	if (isset($_POST['login'])) {
		$timer            = time() - 30;
		$ip_address      = getIpAddr();
		// Getting total count of hits on the basis of IP
		$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM loginlogs WHERE TryTime > :timer and IpAddress = :ip_address";
		$st = $conn->prepare($sql);
		$st->bindValue(":timer", $timer, PDO::PARAM_INT);
		$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
		$st->execute();
		$sql = "SELECT FOUND_ROWS() AS total_count";
		$totalRows = $conn->query($sql)->fetch();
		$total_count     = $totalRows[0];
		$conn = null;
		if ($total_count == 10) {
			$msg = "To many failed login attempts. Please login after 30 sec.";
		} else {
			$total_count++;
			$rem_attm = 10 - $total_count;
			if ($rem_attm == 0) {
				$msg = "<br>To many failed login attempts. Please login after 30 sec.";
			} else {
				$msg = "$rem_attm attempts remaining.";
			}
			$try_time = time();
			$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = "INSERT INTO loginlogs(IpAddress,TryTime) VALUES(:ip_address, :try_time)";
			$st = $conn->prepare($sql);
			$st->bindValue(":ip_address", $ip_address, PDO::PARAM_STR);
			$st->bindValue(":try_time", $try_time, PDO::PARAM_INT);
			$st->execute();
			$conn = null;
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login | <?php echo SITE_TITLE ?></title>
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
						if(isset($results['error'])){
							echo '<div class="alert alert-warning" role="alert">'. $results['error'] . $msg.'</div>';
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
					</form>
				</div>
			</div>
		</div>
	</body>
</html>