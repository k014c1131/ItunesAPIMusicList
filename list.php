<?php

  //createボタンが押されたらDBにnameを作成

  if (isset($_GET['create'])) {
    if($_GET['text'] != ""){
    $name = $_GET['text'];
    $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';
    $user='root';
    $password ='';
      try {
      $dsn= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));
      $sql = 'INSERT INTO list(ListName) VALUES(:name)';
      $stmt = $dsn->prepare($sql);
      $stmt->bindValue(':name', $name);
      $stmt->execute();
    } catch (Exception $ex) {
      print('リストの追加に失敗しました<br>');
    }
    $dsn=NULL;
    header("Location: list.php");
    exit();
  }

  }else if(isset($_GET['delete'])){   //リストをデリートする部分
      $deleteNo = $_GET['delete'];
      $deleteNo = htmlspecialchars($deleteNo,ENT_QUOTES);
      $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';
      $user = 'root';
      $password = '';
      try {
        $dsn = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));

        $sql = 'DELETE FROM  list WHERE id = :delete';
        $stmt = $dsn->prepare($sql);
        $stmt->bindParam(':delete',$deleteNo);
        $stmt->execute();

        $sql3 = 'DELETE FROM  musiclist WHERE ListDBID = :delete';
        $stmt3 = $dsn->prepare($sql3);
        $stmt3->bindParam(':delete',$deleteNo);
        $stmt3->execute();
        $dsn3=NULL;



    } catch (Exception $e) {
        print('データの削除に失敗しました!!<br>');
    }
}
?>

<!DOCTYPE HTML>
<html>
    <head>
    <title>List</title>
        <style type="text/css">
        a { text-decoration: none; }
        table , td, th {
          width: 90%;
          border: 3px solid #595959;
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
      <font size="5">List</font>
      <RIGHT>
        <div style="float:right;">
      <form action="list.php" method="get"><input type="text" name="text">
      <input type="submit" name="create" value="Create"></form>

    </div>
    </RIGHT>
</div>
      <br>
      <hr>
      <br>
      <div Align="center">
        <table>
	<tbody>

      <?php
      //DBから取ってきた値を表示
      //各リスト内の列数を数えて表示(listテーブルのidとmusiclistテーブルのidが同じもの)
      
        $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';//項目の表示
        $user='root';
        $password ='';
          try {
          $dsn= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));

          $sql = 'SELECT * FROM list';
          $stmt = $dsn->prepare($sql);
          $stmt->execute();
          while($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $sql2 = 'SELECT COUNT(*) AS num FROM musiclist WHERE ListDBID = :id';
            $stmt2 = $dsn->prepare($sql2);
            $stmt2->bindValue(':id', $task['id']);
            $stmt2->execute();
            $task2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            echo '<tr><td><a href="ListName.php?List='.$task['id'].'&Listname='.$task['ListName'].'">'.$task['ListName'].'</a></td>';
            echo '<td align="center"><label name="num" value="' . $task2['num'] . '曲";>' . $task2['num'] . '曲</label></form></td>';
            echo '<td align="center"><form method="get" action="list.php"><input type="submit" value="delete"><input type="hidden" name="delete" value="' . $task['id'] . '";></form></td>';
            // echo '<td align="center"><form method="get" action="MusicList.php"><input type="hidden" name="delete" value="' . $task['id'] . '";></form></td></tr>';

          }

        } catch (Exception $ex) {
          print('リストの追加に失敗しました<br>');

      }
        $dsn=NULL;
  ?>

	</tbody>
      </table>
      </div>
  </body>
</html>
