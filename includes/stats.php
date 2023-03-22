<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once( dirname(__FILE__)."/../config.php" );

class Stats {
	//Deprecated since v1.2.0
	
	public static $debug = false;

	public static function debug() {
		if ( Stats::$debug ) :
		$bt     = debug_backtrace();
		$caller = array_shift($bt); ?>
		<pre class='__debug'><?php
		print_r([
			"file"  => $caller["file"],
			"line"  => $caller["line"],
			"args"  => func_get_args()
		]); ?>
		</pre>
		<?php
		endif;
	}
	public static function migration_db() {

		// create table stats
		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$table_db_stats = 'stats';

			$sql = "CREATE TABLE IF NOT EXISTS `".$table_db_stats."` (
				`id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				`page_views` VARCHAR(255),
				`unique_visitor` VARCHAR(255),
				`created_date` DATE
			)";

			$pdo->exec($sql);
			Stats::debug("Table ".$table_db_stats." created successfully<br>");

		} catch(PDOException $e) {

			Stats::debug($sql . "<br>" . $e->getMessage());

		}

		// create table stats_ip_address
		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$table_db_stats_ip_address = 'stats_ip_address';

			$sql = "CREATE TABLE IF NOT EXISTS `".$table_db_stats_ip_address."` (
				`id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				`ip_address` VARCHAR(255),
				`created_date` DATE
			)";

			$pdo->exec($sql);
			Stats::debug("Table ".$table_db_stats_ip_address." created successfully<br>");

		} catch(PDOException $e) {

			Stats::debug($sql . "<br>" . $e->getMessage());

		}
	}
	public static function create_stats( $page_views = 0, $unique_visitor = 0 ){
		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// $ip_address = get_visitor_ip();
			$created_date = date('Y-m-d');

			$sql = "INSERT INTO `stats` (page_views,unique_visitor,created_date) VALUES ('$page_views','$unique_visitor','$created_date')";

			$pdo->exec($sql);
			//Stats::debug("New record created successfully in stats");

		} catch( PDOException $e ) {

			Stats::debug($sql . "<br>" . $e->getMessage());

		}
	}
	public static function update_stats( $page_views = 0, $unique_visitor = 0 ) {
		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// $ip_address = get_visitor_ip();
			$created_date = date('Y-m-d');

			$sql = "UPDATE `stats` SET page_views='$page_views',unique_visitor='$unique_visitor' WHERE `created_date` = '$created_date'";

			$pdo->exec($sql);
			Stats::debug("New record update successfully in stats");

		} catch( PDOException $e ) {

			Stats::debug($sql . "<br>" . $e->getMessage());

		}
	}
	public static function create_stats_ip($ip_address = null) {

		if(!$ip_address){
			$ip_address = Stats::get_visitor_ip();
		}

		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$created_date = date('Y-m-d');

			$sql = "INSERT INTO `stats_ip_address` (ip_address,created_date) VALUES ('$ip_address','$created_date')";

			$pdo->exec($sql);
			//Stats::debug("New record created successfully in stats_ip_address");

		} catch( PDOException $e ) {

			//Stats::debug($sql . "<br>" . $e->getMessage());

		}

	}
	public static function delete_stats_ip() {

		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$created_date = date('Y-m-d');

			$sql = "DELETE FROM stats_ip_address";

			$pdo->exec($sql);
			//Stats::debug("Record deleted successfully in stats_ip_address");

		} catch( PDOException $e ) {

			Stats::debug($sql . "<br>" . $e->getMessage());

		}

	}
	public static function get_stats_ip( $args = [] ) {

		$stats_ip = [];

		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$ip_address = Stats::get_visitor_ip();
			$created_date = date('Y-m-d');

			$sql = "SELECT * FROM `stats_ip_address` WHERE `ip_address` = '$ip_address'";

			$created_date = '';
			if ( isset( $args['created_date']) && !empty( $args['created_date'] ) ) :
				$created_date = $args['created_date'];
			endif;
			if ( $created_date ) :
				$sql .= " AND `created_date` = '$created_date'";
			endif;

			if ( empty($created_date) ) :
				$created_date_before = '';
				if ( isset( $args['created_date_before']) && !empty( $args['created_date_before'] ) ) :
					$created_date_before = $args['created_date_before'];
				endif;
				if ( $created_date_before ) :
					$sql .= " AND `created_date` < '$created_date_before'";
				endif;
			endif;

			$limit = -1;
			$offset = 0;

			if ( isset( $args['limit'] ) && !empty( $args['limit'] ) ) :
				$limit = intval($args['limit']);
			endif;
			if ( isset( $args['offset'] ) && !empty( $args['offset'] ) ) :
				$offset = intval($args['offset']);
			endif;

			if ( !empty($limit) && $limit != -1 ) :
				$sql .= " LIMIT ".$limit." OFFSET ".$offset."";
			endif;

			$stmt = $pdo->prepare($sql);
			$stmt->execute();
		  
			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stats_ip = $stmt->fetchAll();

		} catch(PDOException $e) {

			Stats::migration_db();

			Stats::debug("Error: " . $e->getMessage());

		}

		return $stats_ip;

	}

	public static function is_unique_visitor($ip) {
		$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'SELECT * FROM stats_ip_address WHERE ip_address = :ip';
		$st = $conn->prepare($sql);
		$st->bindValue(":ip", $ip, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		$conn = null;
		if($row){
			return false;
		} else {
			return true;
		}
	}

	public static function update_data() {
		$ip_address = Stats::get_visitor_ip();
		$date_time = date('Y-m-d');

		$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'SELECT * FROM stats WHERE created_date = :date_time';
		$st = $conn->prepare($sql);
		$st->bindValue(":date_time", $date_time, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		if($row){
			$unique_visitor = 0;
			if(Stats::is_unique_visitor($ip_address)){
				$unique_visitor = 1;
				Stats::create_stats_ip($ip_address);
			}
			$sql = 'UPDATE stats SET page_views = page_views + 1, unique_visitor = unique_visitor + :uv WHERE created_date = :date_time';
			$st = $conn->prepare($sql);
			$st->bindValue(":date_time", $date_time, PDO::PARAM_STR);
			$st->bindValue(":uv", $unique_visitor, PDO::PARAM_INT);
			$st->execute();
		} else {
			Stats::delete_stats_ip();
			Stats::create_stats(1,1);
		}
		$conn = null;
		//
		/*$args = [
			'limit'=>1,
			'offset'=>0,
			'created_date'=>date('Y-m-d'),
			// 'created_date_before'=>date('Y-m-d'),
		];
		$stats_ip = Stats::get_stats_ip($args);
		//Stats::debug($stats_ip);
		if ( $stats_ip ) :
			$page_views = 1;
			$unique_visitor = 0;
		else:
			$page_views = 1;
			$unique_visitor = 1;
			Stats::create_stats_ip();
		endif;

		$args = [
			'limit'=>1,
			'offset'=>0,
			// 'created_date'=>date('Y-m-d'),
			'created_date_before'=>date('Y-m-d'),
		];
		$stats_ip = Stats::get_stats_ip($args);
		//Stats::debug($stats_ip);
		if ( $stats_ip ) :
			Stats::delete_stats_ip();
		endif;

		$args = [
			'limit'=>1,
			'offset'=>0,
			'start_date'=>date('Y-m-d'),
			'end_date'=>date('Y-m-d'),
		];
		$stats = Stats::get_data($args);
		Stats::debug($stats);
		if ( $stats ) :
			$new_page_views = $page_views;
			$new_unique_visitor = $unique_visitor;
			if ( isset( $stats[0]['page_views'] ) ) :
				$new_page_views = $page_views+intval($stats[0]['page_views']);
			endif;
			if ( isset( $stats[0]['unique_visitor'] ) ) :
				$new_unique_visitor = $unique_visitor+intval($stats[0]['unique_visitor']);
			endif;
			Stats::update_stats($new_page_views,$new_unique_visitor);
		else:
			Stats::create_stats($page_views,$unique_visitor);
		endif;*/

	}

	public static function get_data( $args = [] ) {

		$stats = [];

		try {

			$pdo = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			$start_date = '';
			$end_date = '';

			if ( isset( $args['start_date']) && !empty( $args['start_date'] ) ) :
				$start_date = $args['start_date'];
			endif;
		
			if ( isset( $args['end_date']) && !empty( $args['end_date'] ) ) :
				$end_date = $args['end_date'];
			endif;

			$sql = "SELECT * FROM `stats`";

			if ( $start_date && $end_date ) :
				$sql .= " WHERE `created_date` BETWEEN '$start_date' AND '$end_date'";
			endif;

			$limit = -1;
			$offset = 0;

			if ( isset( $args['limit'] ) && !empty( $args['limit'] ) ) :
				$limit = intval($args['limit']);
			endif;
			if ( isset( $args['offset'] ) && !empty( $args['offset'] ) ) :
				$offset = intval($args['offset']);
			endif;

			if ( !empty($limit) && $limit != -1 ) :
				$sql .= " LIMIT ".$limit." OFFSET ".$offset."";
			endif;

			$stmt = $pdo->prepare($sql);
			$stmt->execute();
		  
			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stats = $stmt->fetchAll();

		} catch(PDOException $e) {

			Stats::migration_db();

			Stats::debug("Error: " . $e->getMessage());

		}

		return $stats;

	}

	public static function get_data_range( $data ) {
		$args = array(
			'limit' => $data['limit'],
			'offset' => $data['offset'],
			'start_date' => date("Y-m-d", strtotime($data['sub']." days")),
			'end_date' => date('Y-m-d'),
		);
		$result = Stats::get_data($args);
		$conv_result = [];
		foreach($result as $item){
			$conv_result[$item['created_date']] = array('page_views'=>$item['page_views'], 'unique_visitor'=>$item['unique_visitor']);
		}

		$begin = new DateTime( $args['start_date'] );
		$end = new DateTime( $args['end_date'] );
		$end = $end->modify( '+1 day' );

		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);

		$final_result = [];

		foreach($daterange as $date){
			$time = $date->format("Y-m-d");
			if(isset($conv_result[$time])){
				array_push($final_result, array(
					'page_views' => $conv_result[$time]['page_views'],
					'unique_visitor' => $conv_result[$time]['unique_visitor'],
					'date' => $time,
				));
			} else {
				array_push($final_result, array(
					'page_views' => 0,
					'unique_visitor' => 0,
					'date' => $time,
				));
			}
		}
		return $final_result;
	}

	public static function get_visitor_ip() {

		// Get real visitor IP behind CloudFlare network
		if ( isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) :
			$_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
			$_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
		endif;

		$client  = @$_SERVER['HTTP_CLIENT_IP'];
		$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote  = $_SERVER['REMOTE_ADDR'];

		if (filter_var($client, FILTER_VALIDATE_IP)) :
			$ip = $client;
		elseif (filter_var($forward, FILTER_VALIDATE_IP)) :
			$ip = $forward;
		else :
			$ip = $remote;
		endif;

		return $ip;
	}

	public static function init() {
		Stats::migration_db();
		Stats::update_data();
		$args = [
			'limit'=>-1,
			'offset'=>0,
			// 'start_date'=>date('Y-m-d'),
			// 'end_date'=>date('Y-m-d'),
		];
		//$stats = Stats::get_data($args);
		//echo '<pre>'.print_r($stats,1).'</pre>';
	}
}

if( isset($_GET['data']) ){
	$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

	if ( !$username ) {
		exit('logout');
	}
	
	$data = json_decode($_GET['data'], true);
	echo json_encode(Stats::get_data_range($data));
}