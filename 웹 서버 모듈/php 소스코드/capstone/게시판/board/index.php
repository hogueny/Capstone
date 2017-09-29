<?php

	require_once("../dbconfig.php");
	

	if(isset($_GET['page'])) {
		$page = $_GET['page'];
	} else {
		$page = 1;
	}
	
	/* 검색 시작 */
	
	if(isset($_GET['searchColumn'])) {
		$searchColumn = $_GET['searchColumn'];
		$subString .= '&amp;searchColumn=' . $searchColumn;
	}
	if(isset($_GET['searchText'])) {
		$searchText = $_GET['searchText'];
		$subString .= '&amp;searchText=' . $searchText;
	}
	
	if(isset($searchColumn) && isset($searchText)) {
		$searchSql = ' where ' . $searchColumn . ' like "%' . $searchText . '%"';
	} else {
		$searchSql = '';
	}
	
	
	
	$sql = 'select count(*) as cnt from board_free' . $searchSql;
	$result = $db->query($sql);
	$row = $result->fetch_assoc();
	
	$allPost = $row['cnt']; 
	
	if(empty($allPost)) {
		$emptyData = '<tr><td class="textCenter" colspan="5">글이 존재하지 않습니다.</td></tr>';
	} else {

		$onePage = 15; 
		$allPage = ceil($allPost / $onePage); 
		
		if($page < 1 && $page > $allPage) {
?>
			<script>
				alert("존재하지 않는 페이지입니다.");
				history.back();
			</script>
<?php
			exit;
		}
	
		$oneSection = 10; 
		$currentSection = ceil($page / $oneSection); 
		$allSection = ceil($allPage / $oneSection); 
		
		$firstPage = ($currentSection * $oneSection) - ($oneSection - 1);
		
		if($currentSection == $allSection) {
			$lastPage = $allPage; 
		} else {
			$lastPage = $currentSection * $oneSection; 
		}
		
		$prevPage = (($currentSection - 1) * $oneSection); 
		$nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1);
		$paging = '<ul>'; 
		
		
		if($page != 1) { 
			$paging .= '<li class="page page_start"><a href="./index.php?page=1' . $subString . '">처음</a></li>';
		}
		
		if($currentSection != 1) { 
			$paging .= '<li class="page page_prev"><a href="./index.php?page=' . $prevPage . $subString . '">이전</a></li>';
		}
		
		for($i = $firstPage; $i <= $lastPage; $i++) {
			if($i == $page) {
				$paging .= '<li class="page current">' . $i . '</li>';
			} else {
				$paging .= '<li class="page"><a href="./index.php?page=' . $i . $subString . '">' . $i . '</a></li>';
			}
		}
		
		
		if($currentSection != $allSection) { 
			$paging .= '<li class="page page_next"><a href="./index.php?page=' . $nextPage . $subString . '">다음</a></li>';
		}

		if($page != $allPage) { 
			$paging .= '<li class="page page_end"><a href="./index.php?page=' . $allPage . $subString . '">끝</a></li>';
		}
		$paging .= '</ul>';
		
		
		
		
		$currentLimit = ($onePage * $page) - $onePage;
		$sqlLimit = ' limit ' . $currentLimit . ', ' . $onePage; 
		
		$sql = 'select * from board_free' . $searchSql . ' order by b_no desc' . $sqlLimit; 
		$result = $db->query($sql);
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>자유게시판</title>
	<link rel="stylesheet" href="./css/normalize.css" />
	<link rel="stylesheet" href="./css/board.css" />
<style type="text/css">

</style>
</head>
<body>






<table width="100" border="0" align="center">
  <tr>
    <td>
     
   <td align="center">
     
     
  <table align="right">
    <td>	<article class="boardArticle">
      <h3>&nbsp;</h3>
      <h3>문의 게시판</h3>
      <div id="boardList">
        <table border="1">
          
          <thead>
            <tr>
              <th bgcolor="#00FF99" class="no" scope="col">번호</th>
              <th bgcolor="#00FF99" class="title" scope="col">제목</th>
              <th bgcolor="#00FF99" class="author" scope="col">작성자</th>
              <th bgcolor="#00FF99" class="date" scope="col">작성일</th>
              <th bgcolor="#00FF99" class="hit" scope="col">조회</th>
              </tr>
            </thead>
          <tbody>
            <?php
						if(isset($emptyData)) {
							echo $emptyData;
						} else {
							while($row = $result->fetch_assoc())
							{
								$datetime = explode(' ', $row['b_date']);
								$date = $datetime[0];
								$time = $datetime[1];
								if($date == Date('Y-m-d'))
									$row['b_date'] = $time;
								else
									$row['b_date'] = $date;
						?>
            <tr>
              <td class="no"><?php echo $row['b_no']?></td>
              <td class="title">
                <a href="./view.php?bno=<?php echo $row['b_no']?>"><?php echo $row['b_title']?></a>
                </td>
              <td class="author"><?php echo $row['b_id']?></td>
              <td class="date"><?php echo $row['b_date']?></td>
              <td class="hit"><?php echo $row['b_hit']?></td>
              </tr>
            <?php
							}
						}
						?>
            </tbody>
          </table>
        <div class="btnSet">
          <a href="./write.php" class="btnWrite btn">글쓰기</a>
          </div>
        <div class="paging">
          <?php echo $paging ?>
          </div>
        <div class="searchBox">
          <form action="./index.php" method="get">
            <select name="searchColumn">
              <option <?php echo $searchColumn=='b_title'?'selected="selected"':null?> value="b_title">제목</option>
              <option <?php echo $searchColumn=='b_content'?'selected="selected"':null?> value="b_content">내용</option>
              <option <?php echo $searchColumn=='b_id'?'selected="selected"':null?> value="b_id">작성자</option>
              </select>
            <input type="text" name="searchText" value="<?php echo isset($searchText)?$searchText:null?>">
            <button type="submit">검색</button>
            </form>
          </div>
        </div>
      </article></td>
      </tr>
    <tr>
      <td colspan="2"> </td>
      </tr>
</table></td>
  </tr>
</table>
</body>
</html>