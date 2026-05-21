<?php
session_start();


//로그인 안 한 상태면 글 못 쓰게 막기
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

// db 연결하기
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "kknock";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//posts 테이블에 작성자 이름, 제목, 내용 insert
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