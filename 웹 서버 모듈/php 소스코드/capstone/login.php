<?php
session_start(); // 세션
if($_SESSION['id']==null) { // 로그인 하지 않았다면
?>
<style type="text/css">
.c {
	text-align: center;
}
.c {
	text-align: center;
}
.c {
	text-align: center;
}
.c {
	text-align: center;
}
.c {
	text-align: center;
}
</style>


<center>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p><br>
    <img src="main.jpg" width="350" height="100" /><br><br>
  </p>
  <form name="login_form" action="login_check.php" method="post"> 
    <table width="300" border="1" align="center">
      <tr>
        <td width="100" class="c">아이디</td>
        <td width="200" class="c">  <input name="id" type="text" /></td>
      </tr>
      <tr>
        <td width="100" class="c">패스워드 </td>
        <td width="200" class="c"><input name="pw" type="password" /></td>
      </tr>
      <tr>
        <td colspan="2" align="center" valign="middle"><span class="c">
	
			
			
			<input type="button" onclick="location.replace('joinform.php')" value = "Join"> 
			
			
			
			
          <input type="submit" name="login" value="Login" />
        </span></td>
      </tr>
    </table>
    <p>&nbsp;&nbsp;&nbsp;<br><br><br>
    </p>
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
