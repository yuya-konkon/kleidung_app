# kleidung_app

<!-- データベース作成 -->

CREATE DATABASE kleidung_db;

<!-- ユーザー作成 -->

CREATE USER admin identified by 'sasakamaboko';

<!-- 権限付与 -->

GRANT ALL ON kleidung_db.\* to admin;

<!-- ユーザーテーブル -->

CREATE TABLE users (
id int not null PRIMARY KEY auto_increment,
email VARCHAR(32) not null UNIQUE,
user_name VARCHAR(50) not null,
description text,
image VARCHAR(255) not null,
password VARCHAR(255) not null,
created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
updated_at datetime not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

<!-- アイテムズテーブル -->

CREATE TABLE items (
id int not null PRIMARY KEY auto_increment,
user_id int not null,
category_id int not null,
gender_id int not null,
desceiption VARCHAR(255) not null,
photo VARCHAR(255) not null,
created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
updated_at datetime not null DEFAULT CURRENT_TIMESTAMP
);

<!-- ジェンダーテーブル -->

CREATE TABLE gender (
id int not null PRIMARY KEY auto_increment,
name VARCHAR(50) not null,
created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
updated_at datetime not null DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO gender(name) VALUES ('MENS');
INSERT INTO gender(name) VALUES ('WOMENS');
INSERT INTO gender(name) VALUES ('KIDS');

<!-- カテゴリーズテーブル -->

CREATE TABLE categories (
id int not null PRIMARY KEY auto_increment,
name VARCHAR(50) not null,
created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
updated_at datetime not null DEFAULT CURRENT_TIMESTAMP
);
-- 2020 年 3 月 30 日 16:46:13 JST
INSERT INTO categories(name) VALUES ('トップス');
INSERT INTO categories(name) VALUES ('T シャツ/カットソー');
INSERT INTO categories(name) VALUES ('ポロシャツ');
INSERT INTO categories(name) VALUES ('ニット/セーター');
INSERT INTO categories(name) VALUES ('パーカー');
INSERT INTO categories(name) VALUES ('スウェット');
INSERT INTO categories(name) VALUES ('カーディガン');
INSERT INTO categories(name) VALUES ('その他のトップス');
INSERT INTO categories(name) VALUES ('ジャケット/アウター');
INSERT INTO categories(name) VALUES ('テラードジャケット');
INSERT INTO categories(name) VALUES ('ノーカラージャケット');
INSERT INTO categories(name) VALUES ('デニムジャケット');
INSERT INTO categories(name) VALUES ('ライダースジャケット');
INSERT INTO categories(name) VALUES ('ブルゾン');
INSERT INTO categories(name) VALUES ('MA-1');
INSERT INTO categories(name) VALUES ('ダウンジャケット');
INSERT INTO categories(name) VALUES ('ダッフルコート');
INSERT INTO categories(name) VALUES ('モッズコート');
INSERT INTO categories(name) VALUES ('ピーコート');
INSERT INTO categories(name) VALUES ('ステンカラーコート');
INSERT INTO categories(name) VALUES ('トレンチコート');
INSERT INTO categories(name) VALUES ('チェスターコート');
INSERT INTO categories(name) VALUES ('ナイロンジャケット');
INSERT INTO categories(name) VALUES ('マウンテンパーカー');
INSERT INTO categories(name) VALUES ('スタジャン');
INSERT INTO categories(name) VALUES ('スカジャン');
INSERT INTO categories(name) VALUES ('ポンチョ');
INSERT INTO categories(name) VALUES ('その他のアウター');
INSERT INTO categories(name) VALUES ('パンツ');
INSERT INTO categories(name) VALUES ('デニムパンツ');
INSERT INTO categories(name) VALUES ('カーゴパンツ');
INSERT INTO categories(name) VALUES ('チノパンツ');
INSERT INTO categories(name) VALUES ('スラックス');
INSERT INTO categories(name) VALUES ('その他のパンツ');
INSERT INTO categories(name) VALUES ('スカート');
INSERT INTO categories(name) VALUES ('デニムスカート');
INSERT INTO categories(name) VALUES ('その他のスカート');
INSERT INTO categories(name) VALUES ('ワンピース');
INSERT INTO categories(name) VALUES ('シャツワンピース');
INSERT INTO categories(name) VALUES ('ジャンパースカート');
INSERT INTO categories(name) VALUES ('セットアップ');
INSERT INTO categories(name) VALUES ('ドレス');
INSERT INTO categories(name) VALUES ('バッグ');
INSERT INTO categories(name) VALUES ('ショルダーバッグ');
INSERT INTO categories(name) VALUES ('トートバッグ');
INSERT INTO categories(name) VALUES ('バックパック/リュック');
INSERT INTO categories(name) VALUES ('ボストンバッグ');
INSERT INTO categories(name) VALUES ('クラッチバッグ');
INSERT INTO categories(name) VALUES ('メッセンジャーバッグ');
INSERT INTO categories(name) VALUES ('ビジネスバック');
INSERT INTO categories(name) VALUES ('エコバッグ/サブバッグ');
INSERT INTO categories(name) VALUES ('シューズ');
INSERT INTO categories(name) VALUES ('スニーカー');
INSERT INTO categories(name) VALUES ('スリッポン');
INSERT INTO categories(name) VALUES ('サンダル');
INSERT INTO categories(name) VALUES ('パンプス');
INSERT INTO categories(name) VALUES ('ブーツ');
INSERT INTO categories(name) VALUES ('バレエシューズ');
INSERT INTO categories(name) VALUES ('ローファー');
INSERT INTO categories(name) VALUES ('モカシン/デッキシューズ');
INSERT INTO categories(name) VALUES ('レインシューズ');
INSERT INTO categories(name) VALUES ('ビーチサンダル');
INSERT INTO categories(name) VALUES ('その他のシューズ');

<!-- おきにいりテーブル -->

CREATE TABLE favorites (
id int not null PRIMARY KEY auto_increment,
item_id int not null,
user_id int not null,
created_at datetime not null DEFAULT CURRENT_TIMESTAMP,
UNIQUE item_user_id_index(item_id,user_id)
);
