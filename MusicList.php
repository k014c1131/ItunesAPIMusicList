<?php
session_start();
$name="";
if(isset($_SESSION["name"])){
  $name=$_SESSION["name"];//検索ワードの引き継ぎ
}
$error="";

if (isset($_GET["name"])) {
  if(""!=$_GET["name"]){//空文字の時は処理しない
    $name = $_GET["name"];
  }
  $_SESSION["name"] = $name;//ここで検索ワードの引き継ぎをする
    $base_url = 'https://itunes.apple.com/search?term='.$name.'&media=music&entity=song&country=jp&lang=ja_jp&limit=10 ';
    $proxy = array(
      "http" => array(
       "proxy" => "tcp://proxy.kmt.neec.ac.jp:8080",
       'request_fulluri' => true,
      ),
    );
    $proxy_context = stream_context_create($proxy);
    $response = file_get_contents(
                      $base_url,
                      false,
                      $proxy_context
                );
    $result = json_decode($response,true);
}
if(isset($_GET["add"])){
  $dsn ='mysql:dbname=ListDB;host=localhost;charset=utf8';//項目の表示
  $user='root';
  $password ='';
  try {
    $dsn= new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => false));

    $sql = 'INSERT INTO musiclist (username,sdate,message) VALUES(:username,:sdate,:message)';
    $stmt = $dsn->prepare($sql);
    $stmt->bindParam(':username',$username);
    $stmt->bindParam(':sdate',$sdate);
    $stmt->bindParam(':message',$message);
    $stmt->execute();
    while($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo'<option value="'.$task['id'].$select.'">'.$task['ListName'].'</option>';
    }
  } catch (Exception $ex) {
    print('データの追加に失敗しました<br>');
  }
  $dsn=NULL;
}


?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>音楽検索</title>
  </script>
</head>

<body>

  <form action="MusicList.php" method="get">
    検索ワード:<input type="text" name="name" >
    <input type="submit" value="検索"><span id="err" style="color: red;"> <?= $error ?></span>

  <div align="right">
      登録するリスト：<select name="alphabet">
        <?php //print_r($response); ?>
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
            echo'<option value="'.$task['id'].'">'.$task['ListName'].'</option>';
          }
        } catch (Exception $ex) {
          print('データの追加に失敗しました<br>');
        }
        $dsn=NULL;
         ?>
        <option value="B">B</option> 　
        <option value="C">C</option>
      </select></br>
  <button type="button"onclick="location.href='list.php'">マイリストへ</button></br><?php //list.phpへのリンク?>
  </div>
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
  <?php if($name != ""){
    foreach ($result["results"] as $key => $value){
        print('<tr>');
        print('<td bgcolor="#FFFFFF" align="right" nowrap><img src="'.$result["results"][$key]["artworkUrl30"].'"></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["trackName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["artistName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["collectionName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["releaseDate"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300"><audio  src="'.$result["results"][$key]["previewUrl"].'" controls></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="70"><button type="submit" name="add" value="'.$key.'">登録</button></td>');
        print('</tr>');
    }
  }
    ?>

</table>
</form>
</body>
</html>
