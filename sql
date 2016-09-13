音楽の追加
INSERT INTO "List"(SongName,Artist,Album,ReleaseDate,imageUrl) VALUES(音楽名,アーティスト名,アルバム名,発売日,url)

参照
SELECT * FROM "list";
SELECT * FROM MusicList;

テーブルの削除
DROP TABLE "List"

リスト内の音楽の削除
DELETE FROM "List" WHERE id="番号"

"list" → MusicList
