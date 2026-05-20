<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>

<!-- 로그인 폼 -->
<h2>로그인</h2>
<form action="logining.php" method="post">
    <label for="loginUsername">사용자 이름:</label>
    <input type="text" id="loginUsername" name="username" required>
    <br>
    <label for="loginPassword">비밀번호:</label>
    <input type="password" id="loginPassword" name="password" required>
    <br>
    <input type="submit" value="로그인">
</form>

</body>
</html>