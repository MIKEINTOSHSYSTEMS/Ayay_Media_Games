<?php
/**
 * Class to handle game categories
 */

class Category
{
	public $id = null;
	public $name = null;
	public $slug = null;
	public $description = "";
	public $meta_description = "";

	public function __construct($data = array())
	{
		if (isset($data['id'])) $this->id = (int)$data['id'];
		if (isset($data['name'])) $this->name = $data['name'];
		if (isset($data['description'])) $this->description = $data['description'];
		if (isset($data['meta_description'])) $this->meta_description = $data['meta_description'];
    	if ( isset( $data['slug'] ) ) {
    		$this->slug = strtolower(str_replace(' ', '-', basename($data["slug"])));
    	} else {
    		if ( isset( $data['name'] ) ) $this->slug = strtolower(str_replace(' ', '-', basename($data["name"])));
    	}
	}

	public function storeFormValues($params)
	{
		$this->__construct($params);
	}

	public static function getById($id)
	{
		$conn = open_connection();
		$sql = "SELECT * FROM categories WHERE id = :id";
		$st = $conn->prepare($sql);
		$st->bindValue(":id", $id, PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetch();
		if ($row) return new Category($row);
	}

	public static function getBySlug($slug)
	{
		$conn = open_connection();
		$sql = "SELECT * FROM categories WHERE slug = :slug";
		$st = $conn->prepare($sql);
		$st->bindValue(":slug", $slug, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		if ($row) return new Category($row);
	}

	public static function getByName($name)
	{
		$conn = open_connection();
		$sql = "SELECT * FROM categories WHERE name = :name";
		$st = $conn->prepare($sql);
		$st->bindValue(":name", $name, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		if ($row) return new Category($row);
	}

	public static function getIdByName($name)
	{
		$conn = open_connection();
		$sql = "SELECT * FROM categories WHERE name = :name limit 1";
		$st = $conn->prepare($sql);
		$st->bindValue(":name", $name, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		return $row['id'];
	}

	public static function getIdBySlug($slug)
	{
		$conn = open_connection();
		$sql = "SELECT * FROM categories WHERE slug = :slug limit 1";
		$st = $conn->prepare($sql);
		$st->bindValue(":slug", $slug, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		if( $row ) {
			return $row['id'];
		} else {
			return null;
		}
	}

	public static function getList($numRows = 1000000)
	{
		$conn = open_connection();
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
			ORDER BY name ASC LIMIT :numRows";

		$st = $conn->prepare($sql);
		$st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
		$st->execute();
		$list = array();

		while ($row = $st->fetch())
		{
			$category = new Category($row);
			$list[] = $category;
		}

		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query($sql)->fetch();
		return (array(
			"results" => $list,
			"totalRows" => $totalRows[0]
		));
	}

	public static function getCategoryCount($id)
	{
		$conn = open_connection();
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM cat_links
			WHERE categoryid = :id LIMIT 10000";

		$st = $conn->prepare($sql);
		$st->bindValue(":id", $id, PDO::PARAM_INT);
		$st->execute();

		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query($sql)->fetch();
		return $totalRows['totalRows'];
	}

	public static function getListByCategory($id, $amount, $page = 0)
	{
		$conn = open_connection();
		$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM cat_links WHERE categoryid = :id ORDER BY id DESC LIMIT :amount OFFSET :page";
		$st = $conn->prepare($sql);
		$st->bindValue(":id", $id, PDO::PARAM_INT);
		$st->bindValue(":amount", $amount, PDO::PARAM_INT);
		$st->bindValue(":page", $page, PDO::PARAM_INT);
		$st->execute();
		$row = $st->fetchAll();

		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query($sql)->fetch();

		$list = array();
		foreach ($row as $item)
		{
			$game = new Game;
			$res = $game->getById($item['gameid']);
			array_push($list, $res);
		}
		return (array(
			"results" => $list,
			"totalRows" => $totalRows[0],
			"totalPages" => ceil($totalRows[0] / $amount)
		));
	}

	public static function getListByCategories($ids, $amount, $page = 0)
	{
		//
		$conn = open_connection();
		$sql ="SELECT cl1.* FROM `cat_links` as cl1";
		$subsql = " ,(";
		$subsql .= " SELECT DISTINCT `gameid`,`categoryid` FROM `cat_links`";
		if ( $ids ) :
			$subsql .= " WHERE `categoryid` IN (".implode(',', $ids).")";
		endif;
		$subsql .= " LIMIT ".$amount." ) as cl2 ";
		$subsql .= " WHERE cl2.gameid = cl1.gameid";
		$sql = $sql.$subsql;
  		//
		$st = $conn->prepare($sql);
		$st->execute();
		$row = $st->fetchAll();
		//
		$list = array();
		$games_ids = [];
		foreach ( $row as $key => $value ) :
			if ( count($games_ids) > $amount ) :
			  break;
			endif;
			if ( !in_array( $value['gameid'],$games_ids ) ) :
			  $games_ids[] = $value['gameid'];
			endif;
		endforeach;
		//
		foreach ($games_ids as $item)
		{
			$game = new Game;
			$res = $game->getById($item);
			array_push($list, $res);
		}
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query($sql)->fetch();
		return (array(
			"results" => $list,
			"totalRows" => $totalRows[0],
			"totalPages" => ceil($totalRows[0] / $amount)
		));
	}

	public function addToCategory($gameID, $catID)
	{
		$conn = open_connection();
		$sql = "INSERT INTO cat_links ( gameid, categoryid ) VALUES ( :gameID, :catID )";
		$st = $conn->prepare($sql);
		$st->bindValue(":gameID", $gameID, PDO::PARAM_INT);
		$st->bindValue(":catID", $catID, PDO::PARAM_INT);
		$st->execute();
		$this->id = $conn->lastInsertId();
	}

	public function isCategoryExist($name)
	{
		$conn = open_connection();
		$sql = 'SELECT * FROM categories WHERE name = :name limit 1';
		$st = $conn->prepare($sql);
		$st->bindValue(":name", $name, PDO::PARAM_STR);
		$st->execute();
		$row = $st->fetch();
		if ($row)
		{
			$this->id = $row['id'];
		}
		if ($row)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function insert()
	{ 
		if (!is_null($this->id)) trigger_error("Category::insert(): Attempt to insert a Category object that already has its ID property set (to $this->id).", E_USER_ERROR);

		$conn = open_connection();
		$sql = "INSERT INTO categories ( name, slug, description, meta_description ) VALUES ( :name, :slug, :description, :meta_description )";
		$st = $conn->prepare($sql);
		$st->bindValue(":name", $this->name, PDO::PARAM_STR);
		$st->bindValue(":slug", preg_replace('~[^A-Za-z0-9-_.]~','', $this->slug), PDO::PARAM_STR);
		$st->bindValue(":description", $this->description, PDO::PARAM_STR);
		$st->bindValue(":meta_description", $this->meta_description, PDO::PARAM_STR);
		$st->execute();
		$this->id = $conn->lastInsertId();
	}

	public function update()
	{
		if (is_null($this->id)) trigger_error("Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR);
		//$prev_name = Category::getById($this->id)->name;
		//
		$conn = open_connection();
		$sql = "UPDATE categories SET name=:name, slug=:slug, description=:description, meta_description=:meta_description WHERE id = :id";
		$st = $conn->prepare($sql);
		$st->bindValue(":name", $this->name, PDO::PARAM_STR);
		$st->bindValue(":slug", preg_replace('~[^A-Za-z0-9-_.]~','', $this->slug), PDO::PARAM_STR);
		$st->bindValue(":description", $this->description, PDO::PARAM_STR);
		$st->bindValue(":meta_description", $this->meta_description, PDO::PARAM_STR);
		$st->bindValue(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
	}

	public function delete()
	{
		if (is_null($this->id)) trigger_error("Category::delete(): Attempt to delete a Category object that does not have its ID property set.", E_USER_ERROR);

		$conn = open_connection();
		$st = $conn->prepare("DELETE FROM categories WHERE id = :id LIMIT 1");
		$st->bindValue(":id", $this->id, PDO::PARAM_INT);
		$st->execute();
	}

}

?>
