<?php
	require_once("../dbconfig.php");

	//$_GET['bno']이 있어야만 글삭제가 가능함.
	if(isset($_GET['bno'])) {
		$bNo = $_GET['bno'];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>자유게시판 | Kurien's Library</title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
<style type="text/css">
.b {
	font-weight: bold;
}
</style>
</head>
<body>
<center>

	<p>&nbsp;</p>
	<article class="boardArticle">
	  <h3>&nbsp;</h3>
		<?php
			if(isset($bNo)) {
				$sql = 'select count(b_no) as cnt from board_free where b_no = ' . $bNo;
				$result = $db->query($sql);
				$row = $result->fetch_assoc();
				if(empty($row['cnt'])) {
		?>
		<script>
			alert('글이 존재하지 않습니다.');
			history.back();
		</script>
		<?php
			exit;
				}
				
				$sql = 'select b_title from board_free where b_no = ' . $bNo;
				$result = $db->query($sql);
				$row = $result->fetch_assoc();
		?>
		<div id="boardDelete">
			<form action="./delete_update.php" method="post">
				<input type="hidden" name="bno" value="<?php echo $bNo?>">
                <table width="1000" border="0" align="center">
  <tr>
    <td>
			 <table border="1" align="center">
				 <caption class="b">
				 문의 게시판
					글삭제
				<br><br>
				 </caption>
				
				 <tbody>
					 <tr>
						 <th bgcolor="#00FF99" scope="row">글 제목</th>
						 <td><?php echo $row['b_title']?></td>
				     </tr>
					 <tr>
						 <th bgcolor="#00FF99" scope="row"><label for="bPassword">비밀번호</label></th>
						 <td><input type="password" name="bPassword" id="bPassword"></td>
				     </tr>
			     </tbody>
		     </table>
</td></tr></table>



				<div class="btnSet">
				  <button type="submit" class="btnSubmit btn">삭제</button>
					<a href="./index.php" class="btnList btn">목록</a>
			  </div>
			</form>
		</div>
	<?php
		//$bno이 없다면 삭제 실패
		} else {
	?>
		<script>
			alert('정상적인 경로를 이용해주세요.');
			history.back();
		</script>
	<?php
			exit;
		}
	?>
	</article>
</body>
</html>