<?php
class Articles {	
	public $id;
	private $postTable = 'posts_archive';
	private $categoryTable = 'tbl_year_and_section';
	private $userTable = 'acc_user';
    private $studentTable = 'student_acc';
	private $conn;
	public $email;
	public $password;
	
	public function __construct($db){
        $this->conn = $db; 
    }	    
	
    public function getArticles($sortOrder = 'DESC', $limit = null, $offset = null) {
		$query = ''; // Initialize the query variable
	
		if ($this->id) {
			$query = " AND p.id = ?";
		}
	
		$sqlQuery = "
			SELECT p.id, p.title, p.message, p.capstonemembers, p.capstone_advisor, p.capstone_mentor, p.panel_member, p.copyright, p.category_id, u.first_name, u.last_name, p.status, p.created, p.updated, c.name as category
			FROM ".$this->postTable." p
			LEFT JOIN ".$this->categoryTable." c ON c.id = p.category_id
			LEFT JOIN ".$this->userTable." u ON u.id = p.userid
			WHERE p.status ='published' $query ORDER BY p.id $sortOrder";
	
		if ($limit !== null && $offset !== null) {
			$sqlQuery .= " LIMIT ?, ?";
		}
	
		$stmt = $this->conn->prepare($sqlQuery);
	
		if ($this->id) {
			$stmt->bind_param("i", $this->id);
		} elseif ($limit !== null && $offset !== null) {
			$stmt->bind_param("ii", $offset, $limit);
		}
	
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	
	
	
	public function hasMorePages($perPage) {
		$totalPosts = $this->totalPost();
		$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
	
		return ($currentPage * $perPage) < $totalPosts;
	}
	


// Inside the Articles class in Articles.php 
public function authenticateUser($email, $password) {
    $sqlQuery = "SELECT * FROM student_acc WHERE email = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check if $user is not null before accessing the password
        if ($user !== null && isset($user['password'])) {

            // Check if type_of_access is 0
            if ($user['type_of_access'] == 1) {
                return ['error' => 'Access denied pending confirmation'];
            }

            // Verify the password using password_verify
            if (password_verify($password, $user['password'])) {
                // Password is correct, return the user data
                return $user;
            }
        }
    }

    return null;  // Authentication failed
}
// Inside your Articles class
public function getCurrentUserProfile() {
    if ($this->isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $sqlQuery = "SELECT * FROM student_acc WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            return $user;
        }
    }

    return null;  // User not found or not logged in
}
// Inside your Articles class
public function registerUser($firstname, $lastname, $email, $id_number, $type_of_access, $password) {
    // Hash the password before storing it in the database
    $sqlQuery = "
        INSERT INTO ".$this->studentTable." (firstname, lastname, email, id_number, type_of_access, password)
        VALUES (?, ?, ?, ?, ?,?)
    ";

    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, $id_number, $type_of_access, $password);

    return $stmt->execute();
}


public function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

public function bookmarkPost($user_id, $post_id) {
    if ($this->isLoggedIn()) {
        $sqlQuery = "INSERT INTO bookmarks (user_id, post_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bind_param("ii", $user_id, $post_id);
        return $stmt->execute();
    } else {
        return false;
    }
}

  public function getBookmarkedPosts($user_id) {
    $sqlQuery = "SELECT * FROM bookmarks WHERE user_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
// Inside your Articles class
public function isPostBookmarked($user_id, $post_id) {
    $sqlQuery = "SELECT * FROM bookmarks WHERE user_id = ? AND post_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("ii", $user_id, $post_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0;
}
// Inside your Articles class
public function removeBookmark($user_id, $post_id) {
    $sqlQuery = "DELETE FROM bookmarks WHERE user_id = ? AND post_id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("ii", $user_id, $post_id);
    return $stmt->execute();
}

public function getSingleArticleById($article_id) {
    $sqlQuery = "
        SELECT p.*, c.name as category
        FROM " . $this->postTable . " p
        LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
        WHERE p.id = ? AND p.status = 'published'
    ";

    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

	function formatMessage($string, $wordsreturned) {
		$retval = $string;  //  Just in case of a problem
		$array = explode(" ", $string);
		/*  Already short enough, return the whole thing*/
		if (count($array)<=$wordsreturned)
		{
		$retval = $string;
		}
		/*  Need to chop of some words*/
		else
		{
		array_splice($array, $wordsreturned);
		$retval = implode(" ", $array)." ...";
		}
		return $retval;
	}
	
	public function totalPost(){		
		$sqlQuery = "SELECT * FROM ".$this->postTable;			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;	
	}	

	public function getRecentArticleTitles() {
		$query = '';
		if ($this->id) {
			$query = " AND p.id = '" . $this->id . "'";
		}
		$sqlQuery = "
			SELECT p.id, p.title
			FROM " . $this->postTable . " p
			WHERE p.status = 'published' $query
			ORDER BY p.id DESC
			LIMIT 5"; // Limit to the 5 most recent titles
	
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}
	
public function getYearSectionData() {
    $sqlQuery = "SELECT id, name FROM tbl_year_and_section ORDER BY name ASC";
    
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $yearSections = [];
    while ($row = $result->fetch_assoc()) {
        $yearSections[] = $row;
    }
    
    return $yearSections;
}

	

    public function getSingleArticle() {
        // Ensure that $this->id contains a valid ID
        if (!$this->id || $this->id === '0') {
            return null; // Return null to indicate that the article was not found
        }
    
        $sqlQuery = "
            SELECT p.*, c.name as category, b.post_id as bookmark_post_id
            FROM " . $this->postTable . " p
            LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
            LEFT JOIN bookmarks b ON b.post_id = p.id
            WHERE p.id = ? AND p.status = 'published'
        ";
        
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the single article
        } else {
            return null; // Return null if no article with the specified ID was found
        }
    }
    

// Inside your Article class, add the following method
public function getArticlesByYearSection($category_id) {
    // Ensure that $yearsection_id is a valid ID and numeric
    if (!is_numeric($category_id)) {
        return null;
    }

    $sqlQuery = "
    SELECT p.id, p.title, p.message, p.created, p.category_id, c.name as category
    FROM " . $this->postTable . " p
    LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
    WHERE p.category_id = ? AND p.status = 'published'
    ORDER BY p.created ASC";


    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}
public function getYearSectionName($category_id) {
    // Ensure that $category_id is a valid ID and numeric
    if (!is_numeric($category_id)) {
        return null; // Return null to indicate an error
    }

    $sqlQuery = "SELECT name FROM " . $this->categoryTable . " WHERE id = ?";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['name']; // Return the name
    } else {
        return null; // Return null if the category was not found
    }
}
public function searchArticles($searchQuery) {
    // Define your SQL query to search for articles based on various fields
    $sqlQuery = "
        SELECT p.id, p.title, p.message, p.created, p.category_id
        FROM " . $this->postTable . " p
        LEFT JOIN " . $this->categoryTable . " c ON c.id = p.category_id
        WHERE p.status = 'published'
        AND (
            p.title LIKE ? OR
            p.message LIKE ? OR
            p.capstonemembers LIKE ? OR 
            p.capstone_advisor LIKE ? OR
            p.capstone_mentor LIKE ? OR
            p.panel_member LIKE ? OR
            c.name LIKE CONCAT('%', ?, '%')
        )
        ORDER BY p.created DESC
    ";

    $stmt = $this->conn->prepare($sqlQuery);

    // Bind the search query to the SQL statement
    $searchParam = "%" . $searchQuery . "%";
    $stmt->bind_param("sssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    return $result;
}




	
}
?>