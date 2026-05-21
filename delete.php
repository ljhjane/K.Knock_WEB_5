<?php	
	session_start();
	
	error_reporting(E_ALL); //모든 에러를 화면에 표시
	ini_set('display_errors', 1);   //에러 표시 설정 활성화
	
	// 1. 로그인 안 한 상태면 삭제 못 하게 막기
	if (!isset($_SESSION['user_id'])) {//로그인 세션이 없다면
	    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>"; //login.php로 이동
	    exit; //강제 종료
	}

	// 2. 넘겨준 글 번호(id) 받기
	if (isset($_GET["id"])) {
	    $id = $_GET["id"]; //
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
	if ($conn->connect_error) { //연결 실패 시
    die("Connection failed: " . $conn->connect_error); //에러 메시지 출력
    }
	$id= $_GET["id"];
	
	$sql = "delete from posts where id=$id";//posts 테이블에서 작성글 번호와 동일한 튜플을 삭제
	if ($conn->query($sql) === TRUE) {
	    echo "<script>
			location.href='list.php';
		</script>";
	} else {
	    echo "Error: " . $conn->error;
	}

	$conn->close();
?>
