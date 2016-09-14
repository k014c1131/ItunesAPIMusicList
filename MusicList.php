<?php
session_start();
$name="";
if(isset($_SESSION["name"])){
  $name=$_SESSION["name"];//検索ワードの引き継ぎ
}
$error="";
$select=0;
$Displayedresults=10;
if(isset($_GET["alphabet"])){
  $select = $_GET["alphabet"];
}
if(isset($_GET["Displayedresults"])){
  $Displayedresults =$_GET["Displayedresults"];
}
if (isset($_GET["name"])) {
  if(""!=$_GET["name"]){//空文字の時は処理しない
    $name = $_GET["name"];
  }
  $name = htmlspecialchars($name,ENT_QUOTES);
  $name = str_replace(" ", "　",$name);//APIの仕様のため半角スペースを全角に変更
  $_SESSION["name"] = $name;//ここで検索ワードの引き継ぎをする
    $base_url = 'https://itunes.apple.com/search?term='.$name.'&media=music&entity=song&country=jp&lang=ja_jp&limit='.$Displayedresults.' ';
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
    $result["results"][$key]["releaseDate"] = substr($result["results"][$key]["releaseDate"], 0, 10);
    $stmt->bindParam(':ReleaseDate',$result["results"][$_GET["add"]]["releaseDate"]);
    $stmt->bindParam(':ListDBID',$_GET["alphabet"]);
    $stmt->bindParam(':previewUrl',$result["results"][$_GET["add"]]["previewUrl"]);
    $stmt->bindParam(':imageUrl',$result["results"][$_GET["add"]]["artworkUrl30"]);
    $stmt->execute();
    header("Location: MusicList.php?name=&Displayedresults=".$Displayedresults."&alphabet=".$select);
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
  }


</style>
</head>

<body>

  <form action="MusicList.php" method="get">
    検索ワード:<input type="text" name="name" >
    <input type="submit" value="検索"><span id="err" style="color: red;"> <?= $error ?></span>
    表示件数：<select name="Displayedresults">
      <?php
      $array = array(10, 50, 100);
      for ($i=0; $i < 3; $i++) {
        if($Displayedresults==$array[$i]){
            echo'<option value="'.$array[$i].'" selected>'.$array[$i].'件</option>';
        }else{
            echo'<option value="'.$array[$i].'">'.$array[$i].'件</option>';
        }
      }
      ?>
    </select>

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
      <div align="right">
  <button type="button"onclick="location.href='list.php'">マイリストへ</button></br><?php //list.phpへのリンク?>
  </div>
  <table>
    <tr>
      <th></th>
      <th>曲名</th>
      <th>アーティスト</th>
      <th>アルバム</th>
      <th>発売日</th>
      <th>試聴</th>
      <th></th>


    </tr>

  <hr>
  <?php if(isset($result)){
    foreach ($result["results"] as $key => $value){
        $result["results"][$key]["releaseDate"] = substr($result["results"][$key]["releaseDate"], 0, 10);
        print('<tr>');
        print('<td><img src="'.$result["results"][$key]["artworkUrl30"].'"></td>');
        print('<td>'.$result["results"][$key]["trackName"].'</td>');
        print('<td>'.$result["results"][$key]["artistName"].'</td>');
        print('<td>'.$result["results"][$key]["collectionName"].'</td>');
        print('<td>'.$result["results"][$key]["releaseDate"].'</td>');
        print('<td><audio  src="'.$result["results"][$key]["previewUrl"].'" controls></td>');
        print('<td nowrap><button type="submit" name="add" value="'.$key.'">登録</button></td>');
        print('</tr>');
    }
  }
    ?>

</table>
</form>
</body>
</html>
