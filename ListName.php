<?php
//session_start();
$name="";
$error="";
$dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';//項目の表示
$user='root';
$password ='';
$ListDBID=2;
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
  </script>
</head>

<body>
  <form action="ListName.php" method="get">
  <input type="button" value="戻る" onclick="location.href='list.php'"></span>
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
