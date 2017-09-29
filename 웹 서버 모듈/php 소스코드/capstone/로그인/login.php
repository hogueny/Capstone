<?php
session_start(); // 세션
if($_SESSION['id']==null) { // 로그인 하지 않았다면
?>

<center><br><br><br>
[ 원격 가스 검침기 ]<br><br>
<form name="login_form" action="login_check.php" method="post"> 
   ID : <input type="text" name="id"><br> 
   PW: <input type="password" name="pw"><br><br>
   <input type="submit" name="login" value="Login"> 
</form>
</center>

<?php
}else{ // 로그인 했다면
   echo "<center><br><br><br>";
   echo $_SESSION['name']."(".$_SESSION['id'].")님이 로그인 하였습니다.";
   echo "&nbsp;<a href='logout.php'><input type='button' value='Logout'></a>";
   echo "</center>";
}
?>
