<?php
session_start(); // 세션
include ("connect.php"); // DB접속

echo "<p align='right'>"; 
echo $_SESSION['name']."(".$_SESSION['id'].")님이 로그인 하였습니다.";
echo "&nbsp;<a href='update.php' target='_blank'><input type='button' value='정보수정'></a>";
 echo "&nbsp;<a href='logout.php'><input type='button' value='로그아웃'></a>";
echo "<p align='/right'>";
?>



<html>
<body>
<center>
<br>
  <table width="1262" border="0">
    <tr>
      <td><a href="bottom.php" target="bottom"><img src="main.jpg" width="350" height="100" border="0"></a><a href="search.php" target="bottom"><a href="fee.php" target="bottom"></a></td>
      <td valign="bottom"><a href="intro.php" target="bottom"><img src="소개.jpg" width="198" height="50" border="0"></a></td>
      <td valign="bottom"><a href="search.php" target="bottom"><img src="검침량.jpg" width="198" height="50" border="0"></a></td>
      <td valign="bottom"><a href="fee.php" target="bottom"><img src="요금조회.jpg" width="198" height="50" border="0"></a></td>
      <td valign="bottom"><a href="게시판/board/index.php" target="bottom"><img src="게시판.jpg" width="198" height="50" border="0"></a></td>
    </tr>
  </table>
  <table width="100" border="0" align="left">
  <tr>
    <td><img src="라인.JPG" width="1920" height="5"></td>
  </tr>
</table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</center>
</body>
</html>