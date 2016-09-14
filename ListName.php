<?php
session_start();
$Listname="";
$error="";
$dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';//項目の表示
$user='root';
$password ='';
$ListDBID=0;
 if(isset($_GET["List"])){
  $ListDBID=$_GET["List"];
  $_SESSION["List"]=$_GET["List"];
}
if(isset($_SESSION["List"])){
  $ListDBID=$_SESSION["List"];//検索ワードの引き継ぎ
}
if(isset($_GET["Listname"])){
 $Listname=$_GET["Listname"];
 $_SESSION["Listname"]=$_GET["Listname"];
}
if(isset($_SESSION["Listname"])){
  $Listname=$_SESSION["Listname"];//検索ワードの引き継ぎ
}
if(isset($_GET['delete'])){//デリート部分
  $deleteNo = $_GET['delete'];

  $deleteNo = htmlspecialchars($deleteNo,ENT_QUOTES);



try {
  $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';

  $dsn = new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));
  //$dbn->query('SET NAMES utf8');

  $sql = 'DELETE FROM  musiclist WHERE id = :delete';
  $stmt = $dsn->prepare($sql);
  $stmt->bindParam(':delete',$deleteNo);
  $stmt->execute();

  $dsn=NULL;
} catch (Exception $e) {
  print('データの更新に失敗しました!!<br>');
}
}

try {
  $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';
  $user = 'root';
  $password = '';
  $dsn= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));

  $sql = 'SELECT * FROM musiclist WHERE ListDBID = :id';
  $stmt = $dsn->prepare($sql);
  $stmt->bindParam(':id',$ListDBID);
  $stmt->execute();

} catch (Exception $ex) {
  print('データの追加に失敗しました<br>');
}
$dsn=NULL;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>音楽検索</title>
  <style type="text/css">
  table{width: 100%;
  border: 3px solid #000;
  border-collapse:collapse;
  }
  td{
    padding-right: 10px;
    border-bottom: 1px solid #000;
  }
  th{
    border-bottom: 3px solid #000;
    white-space: nowrap;
    background-color: #c8dfeb;
  }

</style>
</head>

<body>
  <form action="ListName.php" method="get">

  <input type="button" value="Back" onclick="location.href='list.php'"></span>
  <?php
  print('<center><font size="5" name="Listname" value="'.$Listname.'">'.$Listname.'</font></center>')
  ?>
  <table  cellspacing="0" cellpadding="5" bordercolor="#333333">
    <tr>
      <th bgcolor="#FFFFFF"></th>
      <th bgcolor="#FFFFFF" width="150">曲名</th>
      <th bgcolor="#FFFFFF" width="200">アーティスト</th>
      <th bgcolor="#FFFFFF" width="200">アルバム</th>
      <th bgcolor="#FFFFFF" width="200">発売日</th>
      <th bgcolor="#FFFFFF">試聴</th>
      <th bgcolor="#FFFFFF"></th>


    </tr>

  <hr>
  <?php if(isset($stmt)){
    while($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
      print('<tr>');
      print('<td bgcolor="#FFFFFF" align="right" nowrap><img src="'.$task["imageUrl"].'"></td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$task["SongName"].'</td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$task["Artist"].'</td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$task["album"].'</td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$task["ReleaseDate"].'</td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="300"><audio  src="'.$task["previewUrl"].'" controls></td>');
      print('<td bgcolor="#FFFFFF" valign="top" width="6%" height="30"><button type="submit" name="delete" value="'.$task["id"].'">delete</button></td>');
      print('</tr>');

    }
  }
    ?>

  </table>
  </form>
</body>
</html>
