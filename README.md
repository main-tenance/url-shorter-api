Использовано официальное docker-окружение Laravel для разработки под управлением Laravel Sail.

API

Создание ссылок
POST /links
Преобразует длинный URL-адрес в короткую ссылку.
long_url string Обязательное. Ссылка, которую нужно сократить
tags array Опциональное. Массив тегов
title string Опциональное. Название для ссылки. По умолчанию - значение тега <title> из URL, который нужно сократить
Пример запроса:
curl \
-H 'Content-Type: application/json' \
-X POST \
-d '{
"long_url": "https://google.com",
"title": "Cool link to google",
"tags": ["homepage", "mylink"]
}' \
https://your.service.com/links
В запросе должна быть реализована возможность передать массив ссылок, которые нужно сократить:
curl \
-H 'Content-Type: application/json' \
-X POST \
-d '[{
"long_url": "https://google.example.com",
"title": "Cool link to google",
"tags": ["search_engines", "google"]
},{
"long_url": "https://yandex.example.com",
},{
"long_url": "https://bing.example.com",
"tags": ["search_engines", "bing"]
}]' \
https://your.service.com/links

Обновление информации о ссылке
PATCH /links/{id}

Удаление ссылки по id
DELETE /links/{id}

Получение ссылки по id
GET /links/{id}

Получение всех ссылок
GET /links

Статистика
Получение статистики по id ссылки
Агрегация по дням, сортировка - по дате по убыванию
GET /stats/{id}
curl -X GET https://your.service.com/stats/12a4b6c
Ответ сервера (200)
total_views - Кол-во всех переходов по ссылке
unique_views - Кол-во переходов по ссылке уникальных пользователей (в зависимости от ip и User Agent)

Получение общей статистики
Cортировать по кол-ву уникальных пользователей по убыванию
GET /stats

Ответ сервера (200)
{
"total_views": "number",
"unique_views": "number"
}
