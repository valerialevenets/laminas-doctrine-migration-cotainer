#!/usr/bin/env bash
# Starts the app
docker-compose up -d --build


docker exec dev_web_1 composer install

echo "Application started"