<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입</title>
</head>
<body>

<!-- 회원가입 폼 -->
<h2>회원가입</h2>
<form action="registering.php" method="post">
    <label for="regUsername">사용자 이름:</label>
    <input type="text" id="regUsername" name="username" required>
    <br>
    <label for="regPassword">비밀번호:</label>
    <input type="password" id="regPassword" name="password" required>
    <br>
    <input type="submit" value="회원가입">
</form>
</body>
</html>