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

$query="INSERT INTO member VALUE ('$id','$pw','$fee','$email')";
$conn->query($query) or die ("입력 오류 : $conn->error");

echo "id : $id <br>";
echo "pw : $pw <br>";
echo "fee : $fee <br>";
echo "email : $email <br>";
echo "등록 성공";
?>
<br>
<a href = 'login.php'> 로그인 </a>
