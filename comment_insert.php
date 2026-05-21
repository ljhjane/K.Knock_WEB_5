<?php
session_start();


// 로그인 안 한 상태면 글 못 쓰게 막기
if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
        alert('로그인이 필요한 서비스입니다.');
        location.href = 'login.php'; 
        </script>
    ";
    exit;
}

$post_id = $_POST['post_id'];
$content = $_POST['content'];
$author_id = $_SESSION['user_id']; // 로그인 세션에서 유저 고유 고유번호(id) 가져오기
$content = $_POST["content"];

//DB 연결 
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "kknock";

if (empty($post_id) || empty($content)) {
    echo "<script>alert('올바르지 않은 접근입니다.'); history.back();</script>";
    exit;
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// comments 테이블에 게시글 번호, 작성자 번호, 댓글 내용, 작성 시각 insert하기
$sql = "INSERT INTO comments (post_id, author_id, content, created_at) VALUES ($post_id, $author_id, '$content', NOW())";


if ($conn->query($sql) === TRUE) {
    echo "
        <script>location.href = 'view.php?id=$post_id#comment_box';</script>
    ";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
