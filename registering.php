<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// MySQL 서버 연결 정보 설정
$servername = "localhost";
$username = "root";
$password = "dlwlgus0717!~";
$dbname = "kknock";

// 사용자로부터 입력된 회원가입 정보
$newUsername = $_POST['username'];
$newPassword = $_POST['password'];

// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO users (username, pw) VALUES ('$newUsername', '$newPassword')";


if ($conn->query($sql) === TRUE) {
    echo "
        <script>
        alert('회원가입이 완료되었습니다!');
        location.href = 'login.php'; // 가입되면 로그인 페이지로 이동
        </script>
    ";
} else {
    echo "Error: " . $conn->error;
}


// 연결 종료
$conn->close();
?>