<?php
	session_start();
	
	// 디버깅용 에러 켜기
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	if(isset($_GET["id"])){ //$_GET["id"] : 레코드 번호
		$num = $_GET["id"];
	}else{
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
	
	$sql = "SELECT p.*, u.username FROM posts p JOIN users u ON p.author_id = u.id WHERE p.id = $num";// posts테이블의 author_id와 users 테이블의 id를 조인하여 posts 테이블의 id가 $num과 동일한 튜플을 찾아 posts 테이블의 모든 내용과 users 테이블의 사용자 이름을 select한다.
	
	
	$result =$conn->query($sql);
	
	if ($result->num_rows == 0) {
    echo "<script>alert('존재하지 않는 게시글입니다.'); location.href='list.php';</script>";
    exit;
}
	
	$row = $result->mysqli_fetch_assoc($result); //select된 튜플 중에서 하나의 레코드를 가져온다
	$author_id = $row["author_id"]; 
	$name = $row["username"];       
	$title = $row["title"];
	$regist_day = $row["created_at"]; 
	$recent_day = $row["updated_at"];
	$content = $row["content"];
	
	//본문 공백 및 줄 바꿈 처리
	$content = str_replace("","&nbsp;", $content); //공백 변환
	$content = str_replace("\n", "<br>", $content); //줄바꿈 변환
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>게시글 보기</title>
	<script>
		function check_pw(mode,num){
			window.open("pw_form.php?mode="+mode+"&num="+num,"pass_check","left=7==,top=300,width=500,height=150.scrollbars=no, resizeable=yes");
		}
	</script>
	</head>
	<body>
		<h2>작성글 보기</h2>
		<ul class="board_view">
			<li class="row1">
				<span class="col1"><b>제목 : </b><?=$title?></span>
				<span class="col2"><?=$name?> | 작성일자 : <?=$regist_day?> 최근수정일자 : <?=$recent_day?>
				</span>
			</li>
			<li class="row2">
				<?=$content?>
			</li>
		</ul>
		<ul class="buttons">
			<li><button onclick="location.href='list.php'">목록보기</button></li>
			<?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $author_id): ?>
            <li><button onclick="location.href='modify_form.php?id=<?=$num?>'">수정하기</button></li>//버튼을 클릭하면 현재 페이지가 modify_form.php로 이동한다.
            <li><button onclick="location.href='delete.php?id=<?=$num?>'">삭제하기</button></li>//버튼을 클릭하면 현재 페이지가 delete.php로 이동한다.
        <?php endif; ?>
			<li><button onclick="location.href='index.php'">작성하기</button></li>
		</ul>
		<hr>
		<h3>댓글</h3>
		<?php
			$comment_sql = "select c.*, u.username FROM comments c JOIN users u ON c.author_id = u.id WHERE c.post_id = $num ORDER BY c.id ASC"; 
            //users 테이블의 id와 comments 테이블의 post_id를 서로 조인한 테이블에서 post_id가 $num인 튜플들을 오름차순으로 정렬하여 comment 테이블의 모든 내용과 users 테이블의 username을 select 
			$commnet_result = $conn->query($commnet_sql);//sql 쿼리를 실행한다.
			
			if ($comment_result->num_rows > 0) { //만약 select하여 나온 튜플의 수가 0보다 크다면
	    while($c_row = $comment_result->fetch_assoc()) { 
		echo "<div style='border-bottom: 1px solid #eee; padding: 5px 0;'>";
		echo "<b>" . $c_row['username'] . "</b>: " . nl2br($c_row['content']); 
		echo " <span style='font-size:12px; color:#888;'>(" . $c_row['created_at'] . ")</span>";
		echo "</div>";
	    }
	} else {
	    echo "<p style='color:#888;'>아직 작성된 댓글이 없습니다.</p>";
	}
		?>
		<br>
<form method="post" action="comment_insert.php">
    <input type="hidden" name="post_id" value="<?=$num?>">
    
    <textarea name="content" rows="3" cols="50" placeholder="댓글을 입력하세요..." required></textarea>
    <br>
    <button type="submit">댓글 등록</button>
	</body>
</html>
<?php
	$conn->close();
?>