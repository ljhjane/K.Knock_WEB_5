<?php
	session_start();
	
	
	// 로그인 안 한 상태면 수정 못 하게 막기
	if (!isset($_SESSION['user_id'])) {
	    echo "<script>alert('로그인이 필요한 서비스입니다.'); location.href='login.php';</script>";
	    exit;
	}
	
	if(isset($_GET["id"])){ //$_GET["id"] : 레코드 번호
		$num = $_GET["id"];
	}else{
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
	
	$sql = "SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = $num";//레코드 검색
	
	
	$result =$conn->query($sql);
	
	if ($result->num_rows == 0) {
    echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
    exit;
}
	
	$row = $result->fetch_assoc(); //레코드 가져오기
	$author_id = $row["author_id"]; 
	$name = $row["username"];       
	$title = $row["title"];
	$content = $row["content"];
	
	//로그인한 사람이 글의 주인인지 확인
	if ($_SESSION['user_id'] != $author_id) {
	    echo "<script>alert('본인의 글만 수정할 수 있습니다.'); history.back();</script>";
	    exit;
	}
	
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>modify</title>
<link rel="stylesheet"  href="style.css">
<script>
	function check_input(){
		if(!document.board.title.value){
			alert("제목을 입력해주세요");
			document.board.subject.focus();
			return;
		}
		if(!document.board.content.value){ 
	                        alert("내용을 입력해주세요");
	                        document.board.content.focus();
	                        return;
	                }
		document.board.submit();

	}
</script>
</head>
<body>
<h2>MODIFY</h2>
<form name="board" method="post" action="modify.php?id=<?=$num?>">
	<ul class="board_form">
		<li>
			<span class="col1">name : </span>
			<span class="col2"><?=$name?></span>
		</li>
		<li>
			<span class="col1">title : </span>
			<span class="col2"><input name="title" type="text" value="<?=$title?>"></span>
		</li>
		<li class="area">
			<span class="col1">cotent : </span>
			<span class="col2"><textarea name="content"><?=$content?></textarea>
			</span>
		</li>
	</ul>
	<ul class="buttons">
		<li><button type="button" onclick="check_input()">
			save</button></li>
		<li><button type="button" onclick="location.href='list.php'">list</button></li>
	</ul>
	</form>
</body>
</html>
<?php
	$conn->close();
?>