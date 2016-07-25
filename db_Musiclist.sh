
mysql -uroot -e "drop database MusicListDB;"
mysql -uroot -e "create database MusicListDB character set UTF8;"
mysql -uroot MusicListDB -e "create table musiclist(id MEDIUMINT NOT NULL AUTO_INCREMENT ,
SongName varchar(256),Artist varchar(256),album varchar(256), ReleaseDate varchar(256),ListDBID int(0),
previewUrl varchar(256),imageUrl varchar(256));"
