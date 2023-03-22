<?php

require('../config.php');
require('../init.php');

if($options['comments'] === 'true'){
	if(isset($_POST['send']) && $login_user){
		$conn = open_connection();
		if(isset($_POST['source']) && $_POST['source'] == 'jquery-comments'){
			if(!$_POST['parent']){
				$_POST['parent'] = null;
			}
			$sql = 'INSERT INTO comments (parent_id, game_id, comment, sender_id, sender_username, created_date) VALUES (:parent_id, :game_id, :comment, :sender_id, :sender_username, :created_date)';
			$st = $conn->prepare($sql);
			$st->bindValue(":parent_id", $_POST['parent'], PDO::PARAM_INT);
			$st->bindValue(":game_id", $_POST['game_id'], PDO::PARAM_INT);
			$st->bindValue(":comment", $_POST['content'], PDO::PARAM_STR);
			$st->bindValue(":sender_id", $login_user->id, PDO::PARAM_INT);
			$st->bindValue(":sender_username", $login_user->username, PDO::PARAM_STR);
			$st->bindValue(":created_date", date('Y-m-d H:m:s'), PDO::PARAM_STR);
			$st->execute();

			$login_user->add_xp(20);

			echo('success');
		}
	}

	if(isset($_POST['load']) && isset($_POST['game_id'])){
		$conn = open_connection();
		$sql = 'SELECT * FROM comments WHERE game_id = :game_id ORDER BY parent_id asc, id asc';
		$st = $conn->prepare($sql);
		$st->bindValue(":game_id", $_POST['game_id'], PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetchAll(PDO::FETCH_ASSOC);
		$list = array();
		foreach ($row as $item) {
			$item['avatar'] = get_user_avatar($item['sender_username']);
			$list[] = $item;
		}
		echo json_encode((array)$list);
	}
}

if(isset($_POST['delete']) && $login_user){
	$conn = open_connection();
	$sql = 'DELETE FROM comments WHERE sender_id = :sender_id AND id = :id LIMIT 1';
	$st = $conn->prepare($sql);
	$st->bindValue(":sender_id", $login_user->id, PDO::PARAM_INT);
	$st->bindValue(":id", $_POST['id'], PDO::PARAM_INT);
	$st->execute();

	echo 'deleted';
}

?>