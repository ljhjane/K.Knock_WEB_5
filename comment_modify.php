<?php
	session_start();

	
	$comment_id = $_GET['id'];
	$post_id = $_GET['post_id'];
	$content = $_POST['content'];

	//db연결하기
	$conn = new mysqli("localhost", "root", "", "kknock");

	
	$sql = "UPDATE comments SET content = '$content' WHERE id = $comment_id";
	$conn->query($sql);
	
	// 현재 페이지를 게시글의 댓글이 있는 위치로 이동하기
	echo "<script>location.href = 'view.php?id=$post_id#comment_box';</script>";
	
	$conn->close();
?>
