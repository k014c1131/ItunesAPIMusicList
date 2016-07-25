#コメントアウトは #を使う。
#最初のファイルです.
#!/bin/bash
mysql -uroot  -e "drop database ListDB;"
mysql -uroot  -e "create database ListDB character set UTF8;"
mysql -uroot  ListDB -e "create table list (id MEDIUMINT NOT NULL AUTO_INCREMENT  ,
ListName varchar(256) ,createDate varchar(256), PRIMARY KEY(id));"

#ListDB -いわゆる、マイリスト
#ListDBに入っている曲名データ用のデータベース
