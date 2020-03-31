# RBC Parser
Ссылка на тестовое задание <http://rbc.slovus.ru/>

### Описание
 Тестовое задание по парсингу и импорту новостей с новостного сайта. Новости парсятся из левой колонки. Парсинг новостей можно запустить по кнопке "Обновить новости RBC" в правом верхнем углу страницы. При каждом запуске скрипта идет добавление новых новостей в базу с проверкой на наличие уже имеющихся.
  
### Что используется:
 - PHP 7.2
 - Symfony 5
 - Компонент ```guzzlehttp/guzzle``` для curl запросов
 - Компонент ```paquettg/php-html-parser``` для парсинга html-контента
 - Компонент ```cron/cron-bundle``` для запуска крон процесса
 - База данных MySQL для сохранения статей

### Установка проекта:
- склонировать проект
- запустить сервер 
  - ```symfony server:start ``` либо
  - ```php -S 0.0.0.0:8000 -t public```
  - В случае установки на сервер ```Apache2``` нужно прописать хост на папку ```public/``` в корне проекта
- ```composer install```
- ```npm install```
- прописать доступы к БД в файле .env в корне проекта
  - ```DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7```
- залить базу данных, запустив миграции из консоля корня проекта
  ```bin/console doctrine:migraions:migrate```

В случае успешной установки проект запустится по ссылке ```http://127.0.0.1:8000/``` либо по указанному хосту

### Запуск импорта новостей
1. Для однократного запуска импорта из веб интерфейса можно нажать на кнопку(ссылку) импорта "Обновить новости RBC" на главной странице
2. Для однократного запуска импорта из консоли запускаем команду в текущей папке проекта ```bin/console cron:run```
3. Для запуска регулярного крон процесса из консоли запускаем команду в текущей папке проекта ```bin/console cron:start```
Подробное описание как настраивать крон смотрим в описании бандла <https://github.com/Cron/Symfony-Bundle>


### Что можно было бы доделать/переделать:
 - Более детально доделать парсинг, чтобы не оставались лишние теги
 - Запускать файл парсинга из консоли и поместить запуск парсинга в крон процесс
 - Постраничка для новостей
 - Сделать модульное тестирование
 
