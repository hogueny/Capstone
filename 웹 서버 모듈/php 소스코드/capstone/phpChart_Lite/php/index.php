 <? php 

  require ( 'db_config.php'); 

  / * demo_viewer 테이블 데이터 가져 오기 * / 
  $ sql = "SUM (numberofview)를 count from FROM demo_viewer로 선택하십시오. 
  GROUP BY YEAR (created_at) ORDER BY created_at "; 
  $ 뷰어 = mysqli_query ($ mysqli, $ sql); 
  $ viewer = mysqli_fetch_all ($ viewer, MYSQLI_ASSOC); 
  $ viewer = json_encode (array_column ($ viewer, 'count'), JSON_NUMERIC_CHECK); 

  / * demo_click 테이블 데이터 얻기 * / 
  $ sql = "SUM (numberofclick)을 count from FROM demo_click으로 선택하십시오. 
  GROUP BY YEAR (created_at) ORDER BY created_at "; 
  $ click = mysqli_query ($ mysqli, $ sql); 
  $ click = mysqli_fetch_all ($ 클릭, MYSQLI_ASSOC); 
  $ click = json_encode (array_column ($ click, 'count'), JSON_NUMERIC_CHECK); 

  ?> 

  <! DOCTYPE html> 
  <html> 
  <머리> 
  <title> HighChart </ title> 
  <link rel = "stylesheet"href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
  <script type = "text / javascript"src = "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"> </ script> 
  <script src = "https://code.highcharts.com/highcharts.js"> </ script> 
  </ head> 
  <body> 

  <script type = "text / javascript"> 

  $ (function () { 

  var data_click = <? php echo $ click;  ?>; 
  var data_viewer = <? php echo $ viewer;  ?>; 

  $ ( '# container'). 하이 차트 ({ 
  차트 : { 
  'column'을 입력하십시오. 
  }, 
  제목 : { 
  텍스트 : '연간 웹 사이트 비율' 
  }, 
  xAxis : { 
  카테고리 : [2013 년, 2014 년, 2015 년, 2016 년] 
  }, 
  yAxis : { 
  제목 : { 
  텍스트 : '평가' 
  } 
  }, 
  시리즈 : [{ 
  이름 : '클릭', 
  데이터 : data_click 
  }, { 
  이름 : '보기', 
  데이터 : data_viewer 
  }] 
  }); 
  }); 

  </ script> 

  <div class = "container"> 
  <br/> 
  <h2 class = "text-center"> 하이 차트 php mysql json 예제 </ h2> 
  <div class = "row"> 
  <div class = "col-md-10 col-md-offset-1"> 
  <div class = "panel panel-default"> 
  <div class = "panel-heading"> 대시 보드 </ div> 
  <div class = "panel-body"> 
  <div id = "container"> </ div> 
  </ div> 
  </ div> 
  </ div> 
  </ div> 
  </ div> 

  </ body> 
  </ html> 