# Отчёт к лабораторной работе №8 Семёнов В.А.
## MySQL
### 1. Установка MySQL

![systemctl status mysql + mysql --version](screenshots/01-mysql-status.png)
### 2. База данных и пользователь

Потому что utf8 - цитирую методичку: "исторический костыль". Т.е это старый вариант UTF-8 в MySQL, который не поддерживает абсолютно все языки и тем более эмодзи. А utf8mb4 же считается полноценным Unicode, потому что поддерживает все языки, эмодзи и современные unicode символы. 

Collation - параметр, который задает по какому принципу сравнивать символы, которые хранятся в БД. Unicode_ci подразумевает точное сравнение Unicode символов по правилам

![SELECT @@character_set_database, @@collation_database;](screenshots/02-db-charset.png)
### 3. phpMyAdmin

![главная страница phpMyAdmin с базой boardy](screenshots/03-phpmyadmin.png)
### 4. Три таблицы

FK - внешний ключ на атрибут другой таблицы. 
ON DELETE CASCADE означает, что при удалении юзера его посты также будут удалены
Движок используется InnoDB, потому что он обеспечивает ACID, что нужно нам, т.к никакой пост не должен теряться, а также нужны FK, чего нет в MyISAM

![SHOW TABLES; + DESCRIBE posts;](screenshots/04-tables-cli.png)
![структура таблицы posts в phpMyAdmin ](screenshots/05-tables-pma.png)
### 5. SQL-скрипт

![содержимое schema.sql](screenshots/06-schema-sql.png)
### 6. INSERT

![SELECT * FROM users; + SELECT * FROM posts;](screenshots/07-data-cli.png)
![вкладка «Обзор» таблицы posts в phpMyAdmin](screenshots/08-data-pma.png)
### 7. SELECT + JOIN

Join используется для того, чтобы можно было объединять несколько таблиц в 1 по связанным в них полям. Получить имя автора без него в 1 таблице можно через подзапрос, т.е:
```
SELECT
    title,
    body,
    (
        SELECT name
        FROM users
        WHERE users.id = posts.author_id
    ) AS author
FROM posts;
```

![ результат ](screenshots/09-join.png)
### 8. Foreign Key — защита целостности

![— ошибка (Cannot add or update a child row)](screenshots/10-fk-error.png)
### 9. CASCADE

Потому что в slow-blocking используется time.sleep(), который полностью блокирует event loop и не дает обработать запросы параллельно, как asyncio.sleep

![COUNT до и после DELETE ](screenshots/11-cascade.png)
### 10. SQL-инъекция

SQL инъекция - это способ встроить в запрос, который формируется напрямую в БД, какой-либо вредоносный SQL код. 
Prepared statement защищает от подобного рода махинаций путем предварительной обработки SQL запроса без переменных. Т.е сначала БД получает SQL код, понимает что за запрос и только после этого получает переменные. 

![результат (все пользователи)](screenshots/12-injection.png)
### 11. db.php

![содержимое db.php](screenshots/13-dp-php.png)
### 12. submit.php через MySQL

![отправка формы, «Спасибо»](screenshots/14-submit.png)
![новая запись в posts (phpMyAdmin → posts → Обзор)](screenshots/15-submit-pma.png )
### 13. messages.php через MySQL

![страница с данными из MySQL](screenshots/16-messages.png)
### 14. aiomysql

Потому что обычный mysql-connector бы блокировал event loop при запросах к БД, а await позволяет работать с ними параллельно 

![curl .../api/messages (JSON из MySQL)](screenshots/17-api-messages.png)
![curl .../api/users (JSON)](screenshots/18-api-users.png)
### Pull-request
![PR](screenshots/19-pull-request.png)
