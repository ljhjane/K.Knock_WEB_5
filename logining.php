<?php
// MySQL 서버 연결 정보 설정
session_start();


$servername = "localhost";
$username = "root";
$pw = "";
$dbname = "kknock";

// 사용자로부터 입력된 로그인 정보
$loginUsername = $_POST['username'];
$loginPw = $_POST['password'];

// MySQL 서버에 연결
$conn = new mysqli($servername, $username, $pw, $dbname);

// 연결 확인
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 로그인 정보를 데이터베이스에서 조회
$sql = "SELECT * FROM users WHERE username= '$loginUsername' AND pw='$loginPw'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 로그인 성공 시 해당 유저의 정보(id)를 세션에 저장
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['username'] = $row['username'];

    echo "
        <script>
        alert('로그인 성공!');
        location.href = 'list.php'; // list.php로 이동
        </script>
    ";
} else {
    echo "
        <script>
        alert('로그인 실패. 아이디나 비밀번호를 확인하세요.');
        history.back(); // 이전 화면으로 돌아가기
        </script>
    ";
}



// 연결 종료
$conn->close();
?>