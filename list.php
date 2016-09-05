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
      <input type="button" value="Back" onclick="history.back()" style="text-align:left;">
                                        //音楽検索の画面から戻るボタンで戻る
    </div>
      List
      <RIGHT>
        <div style="float:right;">
      <INPUT type="text" name="text">
      <input type="button" value="Create" onclick="history.back()">
                                          //新しいリストを作成する
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
                                                                    //リストの削除
		</tr>

	</tbody>
</table>
</div>
    </body>

</html>
