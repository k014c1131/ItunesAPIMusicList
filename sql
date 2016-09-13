
音楽の追加
INSERT INTO "List"(SongName,Artist,Album,ReleaseDate,imageUrl) VALUES("音楽名","アーティスト名","アルバム","発売日","画像URL")
//VALUESには"xxx"の代わりに取得してきた値を入る。

テーブル(リスト)の削除
DROP TABLE "List"

リスト内の音楽の削除
DELETE FROM "List" WHERE id="番号"

//"List"は対応するリストの参照をする。

--hare
