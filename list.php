<!DOCTYPE HTML>
<html>
    <head>
        <title>List</title>
        <style type="text/css">
        table , td, th {
          width: 90%;
          border: 1px solid #595959;
	        border-collapse: collapse;
          }
          td, th  {
	          padding: 3px;
	          width: 90%;
            height: 50px;
}
th {
	background: #f0e6cc;

}
.even {
	background: #fbf8f0;
}
.odd {
	background: #fefcf9;
}
</style>
    </head>
    <body>
<div   style="text-align:center;">

  <div   style="float:left;">
      <input type="button" value="Back" onclick="location.href='MusicList.php'" style="text-align:left;">

    </div>
      List
      <RIGHT>
        <div style="float:right;">
      <INPUT type="text" name="text">
      <input type="button" value="Create" onclick="history.back()">

    </div>
    </RIGHT>
</div>
      <br>
      <br>
      <div Align="center">
      <table>
	<tbody>
		<tr>
			<td>   <div Align="right"><input type="button" value="Delete" onclick="history.back()"></div></td>

      <?php
        $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';//項目の表示
        $user='root';
        $password ='';
        try {
          $dsn= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));

          $sql = 'SELECT * FROM list';
          $stmt = $dsn->prepare($sql);
          $stmt->execute();
          while($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo'<label>'.$task['ListName'].' '.$task['createDate'].'</label>';
            echo'<form method="get" class="log" action="list.php" >';
          }

        } catch (Exception $ex) {
          print('リストの追加に失敗しました<br>');
        }
        $dsn=NULL;
         ?>
		</tr>

	</tbody>

      </table>
      </div>
  </body>

</html>
