#!/bin/sh

# .env がなければ .env.example をコピー
if [ ! -f ./src/.env ]; then
  echo ".env ファイルを作成します"
  cp ./src/.env.example ./src/.env
fi

# Docker コンテナをバックグラウンドで起動
docker-compose up -d

# Laravel のアプリキー生成（初期化）
docker exec -it attendance-management-system-app php artisan key:generate

# アプリケーションコンテナに入って composer install
docker exec -it attendance-management-system-app composer install

# npm install
docker exec -it attendance-management-system-app npm install

# フロントエンドビルド
docker exec -it attendance-management-system-app npm run build
