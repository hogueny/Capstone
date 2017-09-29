<?php
session_start(); // 세션
?>
<html>
<body>

<form action = "update_ok.php" method = "post">
<?php

echo "아이디 : ";
echo $_SESSION['id']."<br>" ; 
?>
비밀번호 : <input type = "password" name="pw"><br>
제한요금 : <input type = "text" name="fee"><br>
이 메 일 : <input type = "text" name="email"><br>

<input type = "submit" value="수정하기"/>
<input type = "reset" value="다시입력"/>
</form>
</body>
</html>
