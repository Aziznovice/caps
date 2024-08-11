<?php
class Post
{
	public $postdetails;
	public $title;
	public $message;
	public $pdfdisplay;
	public $capstonemembers;
	public $capstone_advisor;
	public $capstone_mentor;
	public $panel_member;
	public $copyright;
	public $category;
	public $userid;
	public $status;
	public $created;
	public $updated;
	public $id;
	public $pdf_name;
	private $postTable = 'posts_archive';
	private $categoryTable = 'tbl_year_and_section';
	private $userTable = 'acc_user';
	private $conn;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	// //pdf displaying  
	public function updatePdfName() {
		$query = "UPDATE " . $this->postTable . " SET pdf_name = ? WHERE id = ?";
		$stmt = $this->conn->prepare($query);

		// Sanitize the PDF name
		$sanitizedPdfName = $this->sanitizeTitle($this->pdf_name);

		// Bind parameters
		$stmt->bind_param('si', $sanitizedPdfName, $this->id);

		// Execute query
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function sanitizeTitle($title) {
		// Remove special characters, spaces, and convert to lowercase
		$sanitizedTitle = preg_replace('/[^a-zA-Z0-9.]/', '_', $title);
		$sanitizedTitle = strtolower($sanitizedTitle);

		return $sanitizedTitle;
	}

	// Add or update this method in your Post class
public function updatePdfNameBasedOnTitle()
{
    // Get the current post details
    $postDetails = $this->getPost();
    
    // Sanitize the edited title
    $sanitizedTitle = $this->sanitizeTitle($postDetails['title']);

    // Generate the new PDF name based on the edited title
    $newPdfName = $postDetails['id'] . '_' . $sanitizedTitle . '.pdf';

    // Update the PDF name in the database
    $query = "UPDATE " . $this->postTable . " SET pdf_name = ? WHERE id = ?";
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('si', $newPdfName, $postDetails['id']);

    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}



	//pdf displaying 

	public function getPostsListing() 
	{
		$whereQuery = '';

		// Check if the user is an admin (user_type = 1) or not
		if ($_SESSION['user_type'] == 1) { 
			// Admin can see all posts
			$whereQuery = '';
		} elseif ($_SESSION['user_type'] == 2) {
			// User type 2 can only see their own posts
			$whereQuery = " WHERE p.userid = '" . $_SESSION['userid'] . "'";
		}

		$sqlQuery = "
			SELECT p.id, p.title, p.category_id, p.pdf_name, u.first_name, u.last_name, p.status, p.created, p.updated, c.name 
			FROM " . $this->postTable . " p
			LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
			LEFT JOIN " . $this->userTable . " u ON u.id = p.userid
			$whereQuery";

			if (!empty($_POST["search"]["value"])) {
				if (!empty($whereQuery)) {
					$sqlQuery .= ' AND ';
				} else {
					$sqlQuery .= ' WHERE '; 
				}
				$sqlQuery .= '(title LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR name LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR message LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR capstonemembers LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR capstone_advisor LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR capstone_mentor LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR panel_member LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR created LIKE "%' . $_POST["search"]["value"] . '%" ';
				$sqlQuery .= ' OR updated LIKE "%' . $_POST["search"]["value"] . '%") '; 
			}

			if (!empty($_POST["order"])) {
				// Map DataTables column index to your SQL column index
				$orderColumnIndex = $_POST['order']['0']['column'];
				$orderableColumns = [1, 2, 3, 4, 5, 6]; // Adjust the orderable column indices as needed
			
				if (in_array($orderColumnIndex, $orderableColumns)) {
					// Only add ORDER BY if the column is orderable
					$sqlQuery .= 'ORDER BY ' . $orderableColumns[$orderColumnIndex] . ' ' . $_POST['order']['0']['dir'] . ' ';
				}
			} else {
				$sqlQuery .= 'ORDER BY id DESC ';
			}
			
			$sqlQuery .= ' LIMIT ' . intval($_POST['start']) . ', ' . intval($_POST['length']);
			


		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmtTotal = $this->conn->prepare("SELECT COUNT(*) as total FROM " . $this->postTable);
		$stmtTotal->execute();
		$totalResult = $stmtTotal->get_result();
		$totalRecords = $totalResult->fetch_assoc()['total'];
		

		$displayRecords = $result->num_rows;
		$posts = array();

		while ($post = $result->fetch_assoc()) {
			$status = '';
			if ($post['status'] == 'published') {
				$status = '<span class="label label-success">Published</span>';
			} else if ($post['status'] == 'draft') {
				$status = '<span class="label label-warning">Draft</span>';
			} else if ($post['status'] == 'archived') {
				$status = '<span class="label label-danger">Archived</span>';
			}

			$pdfFileName = isset($post['pdf_name']) ? $post['pdf_name'] : '';
			$pdfFilePath = 'pdf/' . $pdfFileName;

			// Add a custom image icon for PDF downloads
			$pdfIcon = $pdfFileName ? '<a href="' . $pdfFilePath . '" target="_blank"><img src="./css/pdf.png" alt="Download PDF" style="width: 30px;"></a>' : '';
			$rows = array(
				$pdfIcon,
				ucfirst($post['title']),
				$post['name'],
				ucfirst($post['first_name']) . " " . $post['last_name'],
				$status,
				$post['created'],
				$post['updated'],
				'<a href="editpost.php?id=' . $post["id"] . '" class="btn btn-warning btn-xs update">Edit</a>',
				'<button type="button" name="delete" id="' . $post["id"] . '" class="btn btn-danger btn-xs delete">Delete</button>'
			);

			$posts[] = $rows;
		}

		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalRecords,
			"data" => $posts
		);
		

		echo json_encode($output);
	}
	public function getPost()
	{
		if ($this->id) {
			$sqlQuery = "
				SELECT p.id, p.title, p.message, p.capstonemembers, p.capstone_advisor, p.capstone_mentor, p.panel_member, p.copyright, p.category_id, p.status, p.created, p.updated, c.name, p.pdfdisplay, p.pdf_name
				FROM " . $this->postTable . " p
				LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
				WHERE p.id = ? ";
			$stmt = $this->conn->prepare($sqlQuery);
			$stmt->bind_param("i", $this->id);
			$stmt->execute();
			$result = $stmt->get_result();
			$post = $result->fetch_assoc();
			return $post;
		}
	}

	public function insert()
	{

		if ($this->title && $this->message) {

			$stmt = $this->conn->prepare("
    INSERT INTO " . $this->postTable . "(`title`, `message`, `capstonemembers`, `capstone_advisor`, `capstone_mentor`, `panel_member`, `copyright`, `category_id`, `userid`, `status`, `created` , `updated`, `pdfdisplay`)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)");

			$this->title = htmlspecialchars(strip_tags($this->title));
			$this->message = htmlspecialchars(strip_tags($this->message));
			$this->capstonemembers = htmlspecialchars(strip_tags($this->capstonemembers));
			$this->capstone_advisor = htmlspecialchars(strip_tags($this->capstone_advisor));
			$this->capstone_mentor = htmlspecialchars(strip_tags($this->capstone_mentor));
			$this->panel_member = htmlspecialchars(strip_tags($this->panel_member));
			$this->copyright = htmlspecialchars(strip_tags($this->copyright));
			$this->category = htmlspecialchars(strip_tags($this->category));
			$this->userid = htmlspecialchars(strip_tags($this->userid));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->created = htmlspecialchars(strip_tags($this->created));
			$this->updated = htmlspecialchars(strip_tags($this->updated));

			$stmt->bind_param("sssssssiisssi", $this->title, $this->message, $this->capstonemembers, $this->capstone_advisor, $this->capstone_mentor, $this->panel_member, $this->copyright, $this->category, $this->userid, $this->status, $this->created, $this->updated, $this->pdfdisplay);

			if ($stmt->execute()) {
				return $stmt->insert_id;
			}
		}
	}

	public function update()
	{

		if ($this->id) {
			$stmt = $this->conn->prepare("
				UPDATE " . $this->postTable . " 
				SET title= ?, message = ?, capstonemembers = ?, capstone_advisor = ?,capstone_mentor = ?, panel_member = ?, copyright= ?, category_id = ?, status= ?, updated = ?, pdfdisplay=?
				WHERE id = ?");

			$this->id = htmlspecialchars(strip_tags($this->id));
			$this->title = htmlspecialchars(strip_tags($this->title));
			$this->message = htmlspecialchars(strip_tags($this->message));
			$this->capstonemembers = htmlspecialchars(strip_tags($this->capstonemembers));
			$this->capstone_advisor = htmlspecialchars(strip_tags($this->capstone_advisor));
			$this->capstone_mentor = htmlspecialchars(strip_tags($this->capstone_mentor));
			$this->panel_member = htmlspecialchars(strip_tags($this->panel_member));
			$this->copyright = htmlspecialchars(strip_tags($this->copyright));
			$this->category = htmlspecialchars(strip_tags($this->category));
			$this->status = htmlspecialchars(strip_tags($this->status));
			$this->updated = htmlspecialchars(strip_tags($this->updated));

			$stmt->bind_param("sssssssissii", $this->title, $this->message, $this->capstonemembers, $this->capstone_advisor, $this->capstone_mentor, $this->panel_member, $this->copyright, $this->category, $this->status, $this->updated, $this->pdfdisplay, $this->id);

			if ($stmt->execute()) {
				return true;
			}
		}

	}

	public function delete(){
		if($this->id) {    
			// Retrieve the PDF name before deleting the post
			$pdfNameQuery = "SELECT pdf_name FROM " . $this->postTable . " WHERE id = ?";
			$stmtPdfName = $this->conn->prepare($pdfNameQuery);
			$stmtPdfName->bind_param("i", $this->id);
			$stmtPdfName->execute();
			$resultPdfName = $stmtPdfName->get_result();
			$rowPdfName = $resultPdfName->fetch_assoc();
			$pdfName = $rowPdfName['pdf_name'];
	
			// Delete the post
			$deleteQuery = "DELETE FROM " . $this->postTable . " WHERE id = ?";
			$stmtDelete = $this->conn->prepare($deleteQuery);
			$stmtDelete->bind_param("i", $this->id);
	
			if($stmtDelete->execute()){
				// Delete the associated PDF file
				if ($pdfName) {
					$pdfPath = "pdf/" . $pdfName;
					if (file_exists($pdfPath)) {
						unlink($pdfPath);
					}
				}
				return true;
			}
		}
		return false;
	}


	public function getCategories()
	{
		$sqlQuery = "
			SELECT id, name 
			FROM " . $this->categoryTable;

		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}

	public function totalPost()
	{
		$sqlQuery = "SELECT * FROM " . $this->postTable; 
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}
	
	public function getreport() 
	{
		$sqlQuery = "
			SELECT p.id, p.title, p.category_id, p.capstonemembers, p.capstone_advisor, p.capstone_mentor, p.panel_member, p.created, name
			FROM " . $this->postTable . " p
			LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
			LEFT JOIN " . $this->userTable . " u ON u.id = p.userid";
	
		if (!empty($_POST["search"]["value"])) {
			if (strpos($sqlQuery, 'WHERE') === false) {
				$sqlQuery .= ' WHERE ';
			} else {
				$sqlQuery .= ' AND ';
			}
			$sqlQuery .= '(title LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR name LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR capstonemembers LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR capstone_advisor LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR capstone_mentor LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR panel_member LIKE "%' . $_POST["search"]["value"] . '%" ';
			$sqlQuery .= ' OR DATE_FORMAT(created, "%d %M %Y") LIKE "%' . $_POST["search"]["value"] . '%") ';
		}
		$sqlQuery .= ' ORDER BY title ASC';
		
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		$stmtTotal = $this->conn->prepare("SELECT COUNT(*) as total FROM " . $this->postTable);
		$stmtTotal->execute();
		$totalResult = $stmtTotal->get_result();
		$totalRecords = $totalResult->fetch_assoc()['total'];
	
		$displayRecords = $result->num_rows;
		$posts = array();
	
		while ($post = $result->fetch_assoc()) {
			$formattedDate = date_format(date_create($post['created']), "d F Y");
			$rows = array(
				ucfirst($post['title']),
				$post['capstonemembers'],
				$post['name'], 
				$post['capstone_advisor'],
				$post['capstone_mentor'], 
				$post['panel_member'],
				$formattedDate, 
				'<a href="printreport.php?id=' . $post["id"] . '" class="btn btn-primary btn-xs update">Print</a>',
			);
	
			$posts[] = $rows;
		}
	
		$output = array(
			"draw" => intval($_POST["draw"]),
			"recordsTotal" => $totalRecords,
			"recordsFiltered" => $totalRecords,
			"data" => $posts
		);
	
		echo json_encode($output);
	}
	
}
?> 