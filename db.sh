
#!/bin/bash
#コメントアウトは #を使う。
#最初のファイルです.
#ListDB -いわゆる、マイリスト
mysql -uroot  -e "drop database ListDB;"
mysql -uroot  -e "create database ListDB character set UTF8;"
mysql -uroot  ListDB -e "create table list(id MEDIUMINT NOT NULL AUTO_INCREMENT  ,
ListName varchar(256) ,createDate varchar(256), PRIMARY KEY(id));"

#ListDBに入っている曲名データ用のデータベース
#mysql -uroot -e "create database MusicListDB character set UTF8;"
mysql -uroot ListDB -e "create table musiclist(id MEDIUMINT NOT NULL AUTO_INCREMENT ,
SongName varchar(256),Artist varchar(256),album varchar(256), ReleaseDate varchar(256),ListDBID int(0),
previewUrl varchar(256),imageUrl varchar(256), PRIMARY KEY(id));"
mysql -uroot ListDB -e "insert into list(ListName,createDate) values('List1','2016ー01ー01');"
