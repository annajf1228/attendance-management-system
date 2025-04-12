## Attendance Management System

## 環境構築手順

## 1. リポジトリをクローン
```
git clone https://github.com/annajf1228/attendance-management-system.git
```
```
cd attendance-management-system
```

## 2. Dockerコンテナの起動
```
./commands/setup.sh
```

## 3. DBのセットアップ（初回のみ実行してください）
```
./commands/init-db.sh
```

## 5. アクセス
#### ユーザー画面
http://localhost:8000/login
- ログイン情報

| メールアドレス             | パスワード  |
|------------------------|-----------|
| example1@example.com   | test1234  |
| example2@example.com   | test1234  |
| example3@example.com   | test1234  |


#### 管理画面
http://localhost:8000/admin/login
- ログイン情報

| 社員番号  | パスワード   |
|---------|-----------|
| AD1     | test1234  |
| AD2     | test1234  |
| AD3     | test1234  |

#### phpMyadmin
http://localhost:8080/
- ID: user
- PASS: password

## その他
#### 画面構成
- ユーザー画面
```
  ユーザー画面
　├── TOP
　└── 一覧
```
- 管理画面
```
  管理画面
  ├── TOP
  ├── 管理者管理
  ├── スタッフ管理
  └── スタッフ勤怠管理
```
