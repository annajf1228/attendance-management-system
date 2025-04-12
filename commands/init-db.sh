#!/bin/sh

# Laravel のマイグレーションとシーディング
docker exec -it attendance-management-system-app php artisan migrate
docker exec -it attendance-management-system-app php artisan db:seed
