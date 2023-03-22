<?php session_start();
	if(file_exists("connect.php")){
		exit('Installed');
	}

	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
	
	include 'includes/commons.php';
	?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Setup</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="stylesheet" type="text/css" href="admin/style/bootstrap.min.css">
		<!-- Material Design Bootstrap -->
		<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="admin/style/admin.css">
	</head>
	<body class="install-body">
		<div class="install-container">
			<div class="install-form">
				<div class="container">
					<?php
						if(isset($_POST['db_name'])){
							$db_name = preg_replace('~[^A-Za-z0-9?-_-.]~','', $_POST['db_name']);
							$db_host = preg_replace('~[^A-Za-z0-9?-_/\-.]~','', $_POST['db_host']);
							$db_user = preg_replace('~[^A-Za-z0-9?-_-.]~','', $_POST['db_user']);
							$db_password = str_replace(' ','',$_POST['db_password']);
							if($db_name != $_POST['db_name'] || $db_host != $_POST['db_host'] || $db_user != $_POST['db_user'] || $db_password != $_POST['db_password']){
								echo '<div class="alert alert-danger" role="alert">Error. Unsupported characters detected.</div>';
								header('Refresh: 3; url=install.php');
							} else {
								try {
									$conn = new PDO("mysql:host=".$db_host.";dbname=".$db_name, $db_user, $db_password);
									$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									create_tables($conn);
									$_SESSION['db_name'] = $db_name;
									$_SESSION['db_host'] = $db_host;
									$_SESSION['db_user'] = $db_user;
									$_SESSION['db_password'] = $db_password; ?>
								<div class="alert alert-success" role="alert">Database connected successfully</div>
								<form action="install.php" method="POST">
									<div class="form-group">
										<label for="admin_user">Admin username:</label>
										<input type="text" id="admin_user" name="admin_user" class="form-control" value="" required>
									</div>
									<div class="form-group">
										<label for="admin_password">Admin password:</label>
										<input type="text" minlength="6" id="admin_password" name="admin_password" class="form-control" value="" type="password" required>
									</div>
									<button type="submit" class="btn btn-primary btn-md">Submit</button>
								</form>
								<?php
								} catch(PDOException $e) {
									echo '<div class="alert alert-danger" role="alert">Failed. Can\'t connect to database.</div>';
									header('Refresh: 3; url=install.php');
								};
								$conn = null;
							}
						} elseif(isset($_POST['admin_user'])){
							$admin_user_ori = $_POST['admin_user'];
							$admin_user = preg_replace('~[^A-Za-z0-9-_]~','', $_POST['admin_user']);
							if($admin_user == $admin_user_ori){
								$admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
								$filecontent = file_get_contents('connect-sample.php');
								$filecontent = str_replace('db_name', $_SESSION['db_name'], $filecontent);
								$filecontent = str_replace('db_host', $_SESSION['db_host'], $filecontent);
								$filecontent = str_replace('db_user', $_SESSION['db_user'], $filecontent);
								$filecontent = str_replace('db_password', $_SESSION['db_password'], $filecontent);
								file_put_contents("connect.php", $filecontent);
								//Insert new admin users
								$conn = new PDO("mysql:host=".$_SESSION['db_host'].";dbname=".$_SESSION['db_name'], $_SESSION['db_user'], $_SESSION['db_password']);
								$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
								$sql = 'INSERT INTO users ( username, password, role ) VALUES ( :username, :password, :role )';
								$st = $conn->prepare ( $sql );
								$st->bindValue( ":username", $admin_user, PDO::PARAM_STR );
								$st->bindValue( ":password", $admin_password, PDO::PARAM_STR );
								$st->bindValue( ":role", 'admin', PDO::PARAM_STR );
								$st->execute();
								//
								if(is_https()){
									$sql = "UPDATE options SET value = :value WHERE name = :name";
									$st = $conn->prepare($sql);
									$st->bindValue(":name", "url_protocol", PDO::PARAM_STR);
									$st->bindValue(":value", "https://", PDO::PARAM_STR);
									$st->execute();
								}
								$conn = null;
								session_unset();
								?>
								<div class="alert alert-success" role="alert">Setup completed!</div>
								<div class="back-to-site text-center"><a href="admin.php">Login</a></div>
							<?php } else {
								echo '<div class="alert alert-danger" role="alert">Error. Unsupported characters detected.</div>';
								header('Refresh: 3; url=install.php');
							}
						} else { ?>
					<form action="install.php" method="POST">
						<div class="form-group">
							<label for="db_name">Database name:</label>
							<input type="text" class="form-control" id="db_name" name="db_name" value="" required>
						</div>
						<div class="form-group">
							<label for="db_host">Database host:</label>
							<input type="text" class="form-control" id="db_host" name="db_host" value="" required>
						</div>
						<div class="form-group">
							<label for="db_user">Database user:</label>
							<input type="text" class="form-control" id="db_user" name="db_user" value="" required>
						</div>
						<div class="form-group">
							<label for="db_password">Database password:</label>
							<input type="text" class="form-control" id="db_password" name="db_password" value="" type="password">
						</div>
						<button type="submit" class="btn btn-primary btn-md">Submit</button>
					</form>
					<?php }
						function create_tables($conn){
							$query = file_get_contents("db/tables.sql");
							$stmt = $conn->prepare($query);
							$stmt->execute();
						}
					?>
				</div>
			</div>
		</div>
	</body>
</html>