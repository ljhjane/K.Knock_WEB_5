<?php	
	session_start();
	
	// 디버깅용 에러 켜기
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	// 1. 로그인 안 한 상태면 삭제 못 하게 막기
	if (!isset($_SESSION['user_id'])) {
	    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
	    exit;
	}

	// 2. 넘겨준 글 번호(id) 받기
	if (isset($_GET["id"])) {
	    $id = $_GET["id"];
	} else {
	    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
	    exit;
	}
	
	// MySQL 서버 연결 정보 설정
	$servername = "localhost";
	$username = "root";
	$password = "dlwlgus0717!~";
	$dbname = "kknock";


	// MySQL 서버에 연결
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
	$id= $_GET["id"];
	
	$sql = "delete from posts where id=$id";
	if ($conn->query($sql) === TRUE) {
	    echo "<script>
			location.href='list.php';
		</script>";
	} else {
	    echo "Error: " . $conn->error;
	}

	$conn->close();
?>
