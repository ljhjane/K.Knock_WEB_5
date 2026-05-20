<?php
	session_start();

	// 1. 주소창이랑 폼에서 데이터 무조건 받기
	$comment_id = $_GET['id'];
	$post_id = $_GET['post_id'];
	$content = $_POST['content'];

	// 2. DB 문 열고 들어가기
	$conn = new mysqli("localhost", "root", "dlwlgus0717!~", "kknock");

	// 3. 순수하게 내용만 업데이트하기 (보안 기능 다 제외)
	$sql = "UPDATE comments SET content = '$content' WHERE id = $comment_id";
	$conn->query($sql);
	
	// 4. 다 끝났으면 원래 보던 게시글로 튕겨주기
	echo "<script>location.href = 'view.php?id=$post_id#comment_box';</script>";
	
	$conn->close();
?>