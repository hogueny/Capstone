<?php
session_start(); // 세션
if($_SESSION['id']!=null){
   session_destroy();
}
echo "<script>location.href='login.php';</script>";
?>
[출처] PHP 로그인 예제|작성자 초다