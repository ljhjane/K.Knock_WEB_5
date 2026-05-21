<?php
	session_start();
	
	
	// 1. 로그인 안 한 상태면 수정 못 하게 막기
	if (!isset($_SESSION['user_id'])) {
	    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
	    exit;
	}

	// 2. modify_form.php에서 넘겨준 글 번호(id) 받기
	if (isset($_GET["id"])) {
	    $id = $_GET["id"];
	} else {
	    echo "<script>alert('잘못된 접근입니다.'); location.href='list.php';</script>";
	    exit;
	}
	
	// MySQL 서버 연결 정보 설정
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "kknock";


	// MySQL 서버에 연결
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}	

	$title = $_POST["title"];
	$content = $_POST["content"];
	$recent_day= date("Y-m-d (H:i)");
	
	$sql = "update posts set title = '$title', ";
	$sql .= "content='$content', updated_at=NOW() where id=$id";
	
	if ($conn->query($sql) === TRUE) {
	    echo "
		<script>
		alert('글이 성공적으로 수정되었습니다.');
		location.href = 'view.php?id=$id'; 
		</script>
	    ";
	} else {
	    echo "Error: " . $conn->error;
	}
	
	$conn->close();
?>