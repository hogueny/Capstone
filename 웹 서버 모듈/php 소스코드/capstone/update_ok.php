<?php
session_start(); // 세션
?>

<?php
$conn = mysqli_connect('localhost','root','1234','cap');

if($conn -> connect_errno){
die('연결 오류 : '.$conn->connect_error);
}
?>

<?php

$id = $_POST['id'];
$pw = $_POST['pw'];
$fee = $_POST['fee'];
$email = $_POST['email'];

$query="update member set pw = $pw , fee =$fee , email = '$email' where id = '".$_SESSION[id]."'";
$conn->query($query) or die ("입력 오류 : $conn->error");

echo "<center>";
echo "아이디 : $_SESSION[id] <br>";
echo "비밀번호 : $pw <br>";
echo "제한요금 : $fee 원 <br>";
echo "email : $email <br>";
echo "수정 성공";
?>
<br>

<input type="button" value="창닫기" onClick="window.close()"> 


