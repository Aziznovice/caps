<?php
class student {	 
   public $id;
   public $email; 
   public $password; 
   public $first_name;
   public $last_name; 
   public $type_of_access;
   public $deleted; 
   public $updated;
   private $studentTable = 'student_acc';	
   private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }	    	
	public function getstudentListing(){ 		
		
		$sqlQuery = "
			SELECT id, firstname, lastname, email, id_number, type_of_access
			FROM ".$this->studentTable."  
		 ";
		
			if (!empty($_POST["search"]["value"])) {

			$sqlQuery .= ' WHERE ';
			$sqlQuery .= ' firstname LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR lastname LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR email LIKE "%'.$_POST["search"]["value"].'%" ';
			$sqlQuery .= ' OR id_number LIKE "%'.$_POST["search"]["value"].'%" ';			
		}
		if(!empty($_POST["order"])){
			$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
		} else {
			$sqlQuery .= 'ORDER BY id DESC ';
		}
		if($_POST["length"] != -1){
			$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();	
		
		$stmtTotal = $this->conn->prepare("SELECT * FROM ".$this->studentTable);
		$stmtTotal->execute();
		$allResult = $stmtTotal->get_result();
		$allRecords = $allResult->num_rows;
		
		
		$displayRecords = $result->num_rows;
$studentsArray = array(); // Correct variable name

while ($student = $result->fetch_assoc()) {
    $rows = array();
    $status = '';

    if ($student['type_of_access']) {
        $status = '<span class="label label-danger">Inactive</span>';
    } else {
        $status = '<span class="label label-success">Active</span>';
    }

    $rows[] = ucfirst($student['firstname']) . " " . $student['lastname'];
    $rows[] = $student['email'];
    $rows[] = $student['id_number'];
    $rows[] = $status; // Corrected this line to use the $status variable
    $rows[] = '<a href="edit_students.php?id=' . $student["id"] . '" class="btn btn-warning btn-xs update">Edit</a>';
    $rows[] = '<button type="button" name="delete" id="' . $student["id"] . '" class="btn btn-danger btn-xs delete">Delete</button>';
    $studentsArray[] = $rows;
}

$output = array(
    "draw" => intval($_POST["draw"]),
    "iTotalRecords" => $displayRecords,
    "iTotalDisplayRecords" => $allRecords,
    "data" => $studentsArray
);

echo json_encode($output);	 
	}
	
	public function delete(){
		if ($this->id) {  
			$stmt = $this->conn->prepare("
				DELETE FROM ".$this->studentTable."
				WHERE id = ?");
			$this->id = htmlspecialchars(strip_tags($this->id));
			$stmt->bind_param("i", $this->id);
	
			if ($stmt->execute()) {
				return true;
			}
		} else {
			// Handle the case where 'id' is not set
			return false;
		}
	}
	
	public function getstudent(){		
		if($this->id) {
			$sqlQuery = "
			SELECT id, firstname, lastname, email, id_number, type_of_access
			FROM ".$this->studentTable." 			
			WHERE id = ? ";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);	
			$stmt->execute();
			$result = $stmt->get_result(); 
			$user = $result->fetch_assoc();
			return $user;
		}		
	}

	public function update(){
		if($this->id) {            
			$stmt = $this->conn->prepare("
				UPDATE ".$this->studentTable." 
				SET id = ?, firstname = ?, lastname = ?, email = ?, id_number = ?, type_of_access = ?
				WHERE id = ?");
	 
			$this->id = htmlspecialchars(strip_tags($this->id));
			$this->firstname = htmlspecialchars(strip_tags($this->firstname));
			$this->lastname = htmlspecialchars(strip_tags($this->lastname));
			$this->email = htmlspecialchars(strip_tags($this->email));
			$this->password = htmlspecialchars(strip_tags($this->password));
			$this->id_number = htmlspecialchars(strip_tags($this->id_number));
			$this->type_of_access = htmlspecialchars(strip_tags($this->type_of_access));            
				
			$stmt->bind_param("ssssiii", $this->id, $this->firstname, $this->lastname, $this->email, $this->id_number, $this->type_of_access, $this->id);
			
			if($stmt->execute()){
				return true;
			}            
		}
	}

	public function totalstudent(){		
		$sqlQuery = "SELECT * FROM ".$this->studentTable;	 		
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	

	public function getNewStudentCount() {
		$sqlQuery = "SELECT COUNT(*) as count FROM ".$this->studentTable." WHERE added_after_last_visit = 0";
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		$count = $result->fetch_assoc()['count'];
		return $count;
	}
	public function resetAddedAfterLastVisit() {
		$sqlQuery = "UPDATE ".$this->studentTable." SET added_after_last_visit = 1";
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
	}
	public function getAllStudentsCountWithCondition() {
		$sqlQuery = "SELECT * FROM ".$this->studentTable." WHERE added_after_last_visit = 0";	 		
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}
	
	
		
	
}	 
?>