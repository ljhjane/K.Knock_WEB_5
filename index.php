<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>form</title>
<link rel="stylesheet" href="style.css">
<script>
	function check_input(){
		if(!document.board.title.value){
			alert("제목을 입력해주세요");
			document.board.title.focus();
			return;
		}
		if(!document.board.content.value){
			alert("내용을 입력해주세요");
			document.board.content.focus();
		}
		
		document.board.submit();
	}
</script>
</head>
<body>
	<h2>title</h2>
	<form name="board" method="post" action="insert.php">
		<ul class = "board_form">
			<li>
				<span class="col1">제목 : </span>
				<span class="col2"><input name="title" type="text"></span>
			</li>
			<li class="area">
				<span class="col1">내용 : </span>
				<span class="col2"><textarea name="content"></textarea>
				</span>
			</li>
		</ul>
		<ul class="buttons">
			<li><button type="button" onclick="check_input()">저장</button></li>
			<li><button type="button" onclick="location.href='list.php'">list</button></li>
		</ul>
		</form>
</body>
</html>