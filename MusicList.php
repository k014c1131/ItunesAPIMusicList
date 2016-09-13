<?php
session_start();
$name="";
if(isset($_SESSION["name"])){
  $name=$_SESSION["name"];//検索ワードの引き継ぎ
}
$error="";
$select=0;
if(isset($_GET["alphabet"])){
  $select = $_GET["alphabet"];
}
if (isset($_GET["name"])) {
  if(""!=$_GET["name"]){//空文字の時は処理しない
    $name = $_GET["name"];
  }
  $name = htmlspecialchars($name,ENT_QUOTES);
  $name = str_replace(" ", "　",$name);//APIの仕様のため半角スペースを全角に変更
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

    $sql = 'INSERT INTO musiclist (SongName,Artist,album,ReleaseDate,ListDBID,previewUrl,imageUrl) VALUES(:SongName,:Artist,:album,:ReleaseDate,:ListDBID,:previewUrl,:imageUrl)';
    $stmt = $dsn->prepare($sql);
    $stmt->bindParam(':SongName',$result["results"][$_GET["add"]]["trackName"]);
    $stmt->bindParam(':Artist',$result["results"][$_GET["add"]]["artistName"]);
    $stmt->bindParam(':album',$result["results"][$_GET["add"]]["collectionName"]);
    $stmt->bindParam(':ReleaseDate',$result["results"][$_GET["add"]]["releaseDate"]);
    $stmt->bindParam(':ListDBID',$_GET["alphabet"]);
    $stmt->bindParam(':previewUrl',$result["results"][$_GET["add"]]["previewUrl"]);
    $stmt->bindParam(':imageUrl',$result["results"][$_GET["add"]]["artworkUrl30"]);
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
    表示件数：<select name="Displayedresults">
      <option value="10">10件</option>
      <option value="50">50件</option>
      <option value="100">100件</option>
    </select>
  <div align="right">
      登録するリスト：<select name="alphabet">
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

            if($select==$task['id']){
                echo'<option value="'.$task['id'].'" selected>'.$task['ListName'].'</option>';
            }else{
                echo'<option value="'.$task['id'].'">'.$task['ListName'].'</option>';
            }
          }
        } catch (Exception $ex) {
          print('データの追加に失敗しました<br>');
        }
        $dsn=NULL;
         ?>
      </select></br>
  <button type="button"onclick="location.href='list.php'">マイリストへ</button></br><?php //list.phpへのリンク?>
  </div>
  <table  cellspacing="0" cellpadding="5" bordercolor="#333333" width="1000" height="100%">
    <tr>
      <th bgcolor="#FFFFFF" ></th>
      <th bgcolor="#FFFFFF" width="150">曲名</th>
      <th bgcolor="#FFFFFF" width="200">アーティスト</th>
      <th bgcolor="#FFFFFF" width="200">アルバム</th>
      <th bgcolor="#FFFFFF" width="200">発売日</th>
      <th bgcolor="#FFFFFF">試聴</th>
      <th bgcolor="#FFFFFF" width="100"></th>


    </tr>

  <hr>
  <?php if(isset($result)){
    foreach ($result["results"] as $key => $value){
        print('<tr>');
        print('<td bgcolor="#FFFFFF" align="right" nowrap><img src="'.$result["results"][$key]["artworkUrl30"].'"></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["trackName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["artistName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["collectionName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["releaseDate"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300"><audio  src="'.$result["results"][$key]["previewUrl"].'" controls></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="100"><button type="submit" name="add" value="'.$key.'">登録</button></td>');
        print('</tr>');
    }
  }
    ?>

</table>
</form>
</body>
</html>
