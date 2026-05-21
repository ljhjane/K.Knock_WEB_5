<?php
	session_start();
	
	
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
			if (isset($num)) { 
				$current_post_id = $num; // 상단에서 GET으로 받은 $num이 있으면 현재 게시글 번호로 지정
			} elseif (isset($id)) { 
				$current_post_id = $id; // 만약 $id 변수에 값이 있으면 현재 게시글 번호로 지정
			} else { 
				$current_post_id = $_GET['id']; // 둘 다 없으면 주소창에서 직접 id를 가져온다
			}


			// comments 테이블과 users 테이블을 author_id와 id로 join, post_id와 일치하는 댓글의 모든 내용과 작성자의 이름username을 id의 오름차순으로 정렬하여 select
			$comment_sql = "SELECT c.*, u.username FROM comments c JOIN users u ON c.author_id = u.id WHERE c.post_id = $current_post_id ORDER BY c.id ASC";
			
			// 작성한 쿼리문을 데이터베이스에 전송하여 실행하고, 결과 레코드셋을 변수에 저장
			$comment_result = $conn->query($comment_sql);

			// 데이터베이스 조회 결과가 정상적이고, 튜플의 개수가 0 이상일 때
			if ($comment_result && $comment_result->num_rows > 0) {
				while($c_row = $comment_result->fetch_assoc()) {
					// 각 댓글을 시각적으로 구분하기 위해 하단에 회색 테두리와 여백을 주는 구역(div) 생성
					echo "<div style='border-bottom: 1px solid #eee; padding: 10px 0;'>";

					// 주소창(GET)에 'edit_id'가 존재하고, 그 값이 현재 출력 중인 댓글의 고유 ID와 일치하는지 비교하여 '수정 모드' 여부를 판단
					$is_editing = isset($_GET['edit_id']) && $_GET['edit_id'] == $c_row['id'];

					// 만약 사용자가 특정 댓글의 [수정] 버튼을 눌러 수정 모드 상태라면
					if ($is_editing) {
						// 폼 데이터를 POST 방식으로 comment_modify.php에 전송하며, id와 post_id를 전달
						echo "<form method='post' action='comment_modify.php?id=" . $c_row['id'] . "&post_id=" . $current_post_id . "' style='display:inline;'>";
						// 데이터베이스에서 가져온 댓글 작성자의 이름을 굵은 글씨로 화면에 그대로 출력
						echo "<b>" . $c_row['username'] . "</b>: ";
						// 기존에 작성했던 댓글 내용을 수정 입력창(input)의 기본값(value)으로 그대로 채워 넣음
						echo "<input type='text' name='content' value='" . $c_row['content'] . "' style='width:60%; padding:4px;' required>";
						// 누르면 입력된 폼 데이터를 comment_modify.php로 전송하는 완료 버튼 생성
						echo " <button type='submit' style='padding:3px 6px;'>완료</button>";
						// 누르면 수정 모드를 취소하고 #comment_box로
						echo " <button type='button' onclick='location.href=\"view.php?id=" . $current_post_id . "#comment_box\"' style='padding:3px 6px;'>취소</button>";
						echo "</form>"; // 수정 폼 닫기
					} 
					// 수정 모드가 아닌 일반적인 댓글 출력 상태라면
					else {
						// username과 content를 변수 그대로 화면에 출력 
						echo "<b>" . $c_row['username'] . "</b>: " . nl2br($c_row['content']);
						// 
						echo " <span style='font-size:12px; color:#888;'>(" . $c_row['created_at'] . ")</span>";

						// user_id가 comments 테이블의 author_id와 일치하는지 권한 확인
						if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $c_row['author_id']) {
							// 일치할 경우 현재 페이지를 수정 모드로 전환시키는 [수정] 링크 출력
							echo " <a href='view.php?id=" . $current_post_id . "&edit_id=" . $c_row['id'] . "#comment_box' style='font-size:12px;  text-decoration:none;'>[수정]</a>";
							// 일치할 경우 comment_delete.php로 댓글 ID와 게시글 ID를 넘겨 레코드를 삭제하는 [삭제] 링크 출력
							echo " <a href='comment_delete.php?id=" . $c_row['id'] . "&post_id=" . $current_post_id . "' onclick='return confirm(\"정말 삭제하시겠습니까?\")' style='font-size:12px; color:red; text-decoration:none;'>[삭제]</a>";
						}
					}

					echo "</div>"; 
				}
			} 
			// 조회된 튜플이 0개일때 = 댓글이 없을 때
			else {
				echo "<p style='color:#888;'>아직 작성된 댓글이 없습니다.</p>"; 
			}
		?>
		<br>
		<br>
<form method="post" action="comment_insert.php">
    <input type="hidden" name="post_id" value="<?=$num?>">
    
    <textarea name="content" rows="3" cols="50" placeholder="댓글 작성하기" required></textarea>
    <br>
    <button type="submit">댓글 등록</button>
	</body>
</html>
<?php
	$conn->close();
?>