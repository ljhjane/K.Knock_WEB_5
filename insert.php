<?php
session_start();

// 디버깅용 에러 켜기
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. 로그인 안 한 상태면 글 못 쓰게 막기
if (!isset($_SESSION['user_id'])) {
    echo "
        <script>
        alert('로그인이 필요한 서비스입니다.');
        location.href = 'login.php'; 
        </script>
    ";
    exit;
}

$author_id = $_SESSION['user_id']; // 로그인 세션에서 유저 고유 고유번호(id) 가져오기
$title = $_POST["title"];
$content = $_POST["content"];

// 2. DB 연결 (아까 성공한 정보 그대로!)
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "kknock";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 3. 안전하게 쿼리 준비해서 posts 테이블에 넣기
$sql = "INSERT INTO posts (author_id, title, content) VALUES ($author_id, '$title' , '$content')";


if ($conn->query($sql) === TRUE) {
    echo "
        <script>
        alert('글이 성공적으로 등록되었습니다.');
        location.href = 'list.php'; 
        </script>
    ";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>