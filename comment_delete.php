<?php	
	session_start();
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	if (!isset($_SESSION['user_id'])) {
	    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
	    exit;
	}

	
	// MySQL 서버 연결 정보 설정
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "kknock";

	$comment_id = $_GET['id'];
	$post_id = $_GET['post_id'];
	$author_id = $_SESSION['user_id'];
	
	// MySQL 서버에 연결
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);

	}
	
	$sql = "delete from comments where id=$comment_id and author_id = $author_id";
	if ($conn->query($sql) === TRUE) {
	    echo "<script>
			alert('댓글이 삭제되었습니다.'); location.href='view.php?id=$post_id';
		</script>";
	} else {
	    echo "Error: " . $conn->error;
	}

	$conn->close();
?>
