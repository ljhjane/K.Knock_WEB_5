<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>글 목차</title>
</head>
<body>
	<h2>목록 보기</h2>
	<ul class="board_list">
		<li>
			<span class="col1">번호</span>
			<span class="col2">제목</span>
			<span class="col3">작성자</span>
			<span class="col4">등록일</span>
			<span class="col5">최근 수정일자</span>
		</li>
		<?php
		
		session_start();

// 디버깅용 에러 켜기
error_reporting(E_ALL);
ini_set('display_errors', 1);
			// MySQL 서버 연결 정보 설정
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kknock";


// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
			$sql = "SELECT p.*, u.username 
        FROM posts p 
        JOIN users u ON p.author_id = u.id 
        ORDER BY p.id DESC";
			$result =$conn->query($sql);
			$total_record = $result->num_rows;

			$number = $total_record;
			for($i=0; $i<$total_record; $i++){
				mysqli_data_seek($result, $i); //레코드 포인터 이동
				$row = mysqli_fetch_assoc($result); //레코드 가져오기

				$num = $row["id"]; //레코드 번호
				$name = $row["author_id"]; //이름
				$subject = $row["title"]; //제목
				$regist_day = $row["created_at"]; //작성일
				$recent_day = $row["updated_at"];
					
		?>
			<li>
				<span class="col1"><?=$number?></span>
				<span class="col2"><a href="view.php?id=<?=$row['id']?>"><?=$row['title']?></a></span>
				<span class="col3"><?=$name?></span>
				<span class="col4"><?=$regist_day?></span>
				<span class="col5"><?=$recent_day?></span>
			</li>
	<?php
		$number--;
	}
	$conn->close();
	?>
	</ul>
	<ul class="buttons">
	<li><button onclick="location.href='list.php'">목록</button></li>
	<li><button onclick="location.href='index.php'">작성하기</button></li>
	</ul>
</body>
</html>