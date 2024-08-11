<?php
class Category { 
	public $name;
	public $id;
	private $categoryTable = 'tbl_year_and_section';	 
	private $titlestable = 'posts_archive';
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	
	
	public function getCategoryListing() {
    $sqlQuery = "
        SELECT c.id, c.name as school_year, COUNT(t.id) as total_titles
        FROM ".$this->categoryTable." c
        LEFT JOIN ".$this->titlestable." t ON c.id = t.category_id
    ";

    if (!empty($_POST["search"]["value"])) {
        $sqlQuery .= ' WHERE c.name LIKE "%'.$_POST["search"]["value"].'%" ';
    }

    $countQuery = "SELECT COUNT(DISTINCT c.id) as total FROM ".$this->categoryTable." c";
    
    $stmtTotal = $this->conn->prepare($countQuery);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $rowTotal = $resultTotal->fetch_assoc();
    $totalRecords = $rowTotal['total'];

    if (!empty($_POST["order"])) {
        $columnIndex = $_POST['order']['0']['column'];
        $columnName = $_POST['columns'][$columnIndex]['data'];
        $sqlQuery .= ' GROUP BY c.id ORDER BY ' . $columnName . ' ' . $_POST['order']['0']['dir'] . ' ';
    } else {
        $sqlQuery .= ' GROUP BY c.id ORDER BY c.id DESC ';
    }

    if ($_POST["length"] != -1) {
        $sqlQuery .= ' LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
    }

    $stmt = $this->conn->prepare($sqlQuery); 
    $stmt->execute();
    $result = $stmt->get_result();

    $displayRecords = $result->num_rows;
    $categories = array();

    while ($row = $result->fetch_assoc()) {
        $rows = array();
        $rows[] = $row['id'];
        $rows[] = $row['school_year'];
        $rows[] = $row['total_titles'];
        $rows[] = '<a href="editcategories.php?id='.$row["id"].'" class="btn btn-warning btn-xs update">Edit</a>';
        $rows[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete" >Delete</button>';
        $categories[] = $rows;
    }

    $output = array(
        "draw"  => intval($_POST["draw"]),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" =>  $totalRecords,
        "data"  => $categories
    );

    echo json_encode($output);
}

	
	
	
	public function getCategory(){		
		if($this->id) {
			$sqlQuery = "
			SELECT id, name
			FROM ".$this->categoryTable." 			
			WHERE id = ? ";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result();
			$category = $result->fetch_assoc();
			return $category;
		}		
	}
	
	public function insert(){
		
		if($this->name) {

			$stmt = $this->conn->prepare("
				INSERT INTO ".$this->categoryTable."(`name`)
				VALUES(?)");
		
			$this->name = htmlspecialchars(strip_tags($this->name));						
			$stmt->bind_param("s", $this->name);
			
			if($stmt->execute()){
				return $stmt->insert_id;
			}		
		}
	}
	
	public function update(){
		
		if($this->id) {			
			$stmt = $this->conn->prepare("
				UPDATE ".$this->categoryTable." 
				SET name= ?
				WHERE id = ?");
	 
			$this->id = htmlspecialchars(strip_tags($this->id));
			$this->name = htmlspecialchars(strip_tags($this->name));			
			
			$stmt->bind_param("si", $this->name, $this->id);
			 
			if($stmt->execute()){
				return true;
			}			
		}
		
	}
	
	public function delete(){
		
		if($this->id) {	
		
			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->categoryTable." 				
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));

			$stmt->bind_param("i", $this->id);

			if($stmt->execute()){
				return true;
			}
		}
	}
	
	public function totalCategory(){		
		$sqlQuery = "SELECT * FROM ".$this->categoryTable;			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	
}
?>