<?php
$name="";
$error="";
if (isset($_GET["name"])) {
  $name = $_GET["name"];

  if ($name==="") {
    $error = "IDを入力してください";
  } else {
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
  <?php //print_r($response); ?>
  <input type="button" value="戻る" onclick="location.href='list.php'"></span>
  </form>
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
  <?php if($name!=""){
    foreach ($result["results"] as $key => $value){
        print('<tr>');
        print('<td bgcolor="#FFFFFF" align="right" nowrap><img src="'.$result["results"][$key]["artworkUrl30"].'"></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["trackName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["artistName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["collectionName"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300">'.$result["results"][$key]["releaseDate"].'</td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="300"><audio  src="'.$result["results"][$key]["previewUrl"].'" controls></td>');
        print('<td bgcolor="#FFFFFF" valign="top" width="70"><button type="submit" name="button" value="'.$key.'">登録</button><input type="submit" name="button" value="'.$key.'"></td>');
        print('</tr>');
    }
  }
    ?>

</table>

</body>
</html>
