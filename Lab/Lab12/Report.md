# Отчёт к лабораторной работе №12 Семёнов В.А.
## Laravel: переезд на фреймворк (MVC + Breeze + Socialite)
### 1.  Composer и PHP-расширения

![comsposer](screenshots/01-composer-php.png  )
### 2. Переезд папок

![— ls /var/www](screenshots/02-folders.png  )
![cd /var/www/boardy ](screenshots/03-laravel-version.png  )
### 3. Структура Laravel

app/ - основной код
routes/ - файлы эндпоинтов
resources/views/ - шаблоны HTML
database/ - миграции
pubic/ - папка наружу (точка входа index.php и статика)

Защитный вопрос - ответ: в корне лежат рабочие файлы, по типу .env и т.п, которые мы не хотим выставлять наружу

### 4. Nginx-конфиг

говорит nginx сначала пробовать отдавать по этому адресу файл, а если его нет, то отправлять на index.php ларавелю. если убрать эту строку, то при /post/3 будет просто 404

![cat /etc/nginx/sites-available/boardy.](screenshots/04-nginx-config.png )
![приветственная страница Laravel ](screenshots/05-laravel-welcome.png )
### 5. Создание БД boardy_main

схема старой бд сделано под простой PHP, а подгонять старую под ларавель более затруднительно, нежели просто создать новую

![— mysql -e "SHOW DATABASES](screenshots/06-databases.png )
### 6. Подключение Laravel к БД

![07-tinker-pdo.png d](screenshots/07-tinker-pdo.png )
### 7. Миграции posts и comments

![php artisan migrate:status ](screenshots/08-migrate-status.png )
![mysql -u boardy -p boardy_main ](screenshots/09-show-tables.png  )
### 8. Модели со связями

![php artisan tinker  ](screenshots/10-model-relations.png )
### 9. Сидер

![tinker → User::count(), Post](screenshots/11-seed-counts.png )
### 10. Маршруты

![php artisan route:list ](screenshots/12-route-list.png )
### 11. Лента постов

![лента /posts.](screenshots/13-posts-index.png )
### 12. Страница поста с комментариями

потому что пользователь может включить приватность своей почты и мы ее не увидим, а айди гитхаб аккаунта точно одно и уникальное

![/posts/3 с постом](screenshots/14-post-show.png)
### 13. Создание поста

![форма /posts/create.](screenshots/15-post-create.png )
![страница созданного поста.](screenshots/16-post-after-create.png )
### 14. Policy и редактирование

Мы вручную везде проверяли текущего пользовать с записью из БД, после чего возвращали 403 либо редирект
А тут мы выносим это в отдельный класс, после чего добавляет в контроллер зависимость

![— кнопки «Редактировать» и «Удалить» под СВОИМ постом](screenshots/17-edit-own.png )
![— попытка открыть /posts/X/edit чужого поста → 403 Forbidden](screenshots/18-edit-foreign-403.png )
### 15. Удаление поста

![пост удалён, в ленте его нет(screenshots/19-post-deleted.png )
### 16. Комментарий через Blade

![комментарий после отправки виден на странице поста ](screenshots/20-comment-created.png)
### 17. Установка Breeze

![register ](screenshots/21-register.png )
![login ](screenshots/22-login.png)
### 18. Регистрация и вход

![состояние после регистраци ](screenshots/23-after-register.png)
### 19. GitHub OAuth-приложение

![страница вашего OAuth App на GitHub ](screenshots/24-github-app.png)
### 20. Socialite

![страница /login с кнопкой «Войти через GitHub». ](screenshots/25-login-with-github.png )
### 21. Полный OAuth flow

![страница GitHub Authorize. ](screenshots/26-github-authorize.png )
![состояние после успешного OAuth-входа ](screenshots/27-after-github-login.png )
![— SELECT id, name, email, github_id FROM users WHERE github_id IS NOT NULL](screenshots/28-mysql-github-id.png )
### 22. Что осталось от прошлых практик

Мы их не удалили, потому что это наработанные файлы, с помощью которых мы руками трогали то, что происходит под капотом тех технологий, который мы теперь используем. Если попробовать открыть /login.php, то выдаст File not found, потому что теперь Laravel работает через public/ и наш веб-сервер не видит старую директорию с /login.php

### 23. FastAPI и React

Сейчас нам это просто не нужно, Laravel это самостоятельное бэкенд приложение, поэтому смешивать 2 фронта без четкого разделения бессмысленно. А вот когда у нас появится явная микросервисная архитектура, тогда мы сможем прикрутить React для динамического обновления через вебсокеты

### 24. Реалтайм

Нам нужны вебсокеты! Это протокол обмена данными по двустороннему постоянному каналу, благодаря чему мы можем доносить информацию о свежих данных клиенту сами. Два сервера-кандидата это Laravel Reverb и FastAPI, потому что Laravel будет генерировать события о новых действиях, а FastAPI как отдельный API, с помощью которого мы сможем реализовать реалтайм
