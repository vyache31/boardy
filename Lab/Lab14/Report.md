# Отчёт к лабораторной работе №14 Семёнов В.А.
## Final
### 1.  Установка и SPA-клиент

SPA в браузере, там негде спрятать секрет, поэтому его может увидеть любой в коде. PKCE заменяет клиентский секрет путем создания динамического code_verifier и code_challenge. Защищает от code inter attack

![1](screenshots/01-passport-install.png )
![2](screenshots/02-spa-client.png )
### 2. TTL и refresh

Access передается при каждом защищенном запросе, поэтому короткий срок жизни приносит меньше вреда при утечке этого токена. А рефреш токен используется реже и храниться в httpOnly куке, что защищает от XSS атак

если access будет валиден сутки, то при его утечке у злоумышленника будет 24 часа доступа к аккаунту

![3](screenshots/03-token-ttl.png  )
### 3. Проверка выдачи через curl

1. браузер открывает `/oauth/authorize` с параметрами.
    
2. пользователь логинится.
    
3. Authorization Response: возвращается `code`.
    
4.  curl отправляет POST `/oauth/token` с `code` и `code_verifier`.
    
5. сервер возвращает `access_token` и `refresh_token`.  
    PKCE добавляет проверку: `code_verifier` должен хэшироваться в тот же `code_challenge`.

![4](screenshots/04-pkce-curl.png )
### 4. Создание boardy_api

Потому что посты и юзеры в другой бд. Целостность поддерживает приложение через редис паб/саб

![5.](screenshots/05-databases.png )
![6 ](screenshots/06-comments-schema.png )
### 5. FastAPI подключён к новой БД

![7](screenshots/07-fastapi-db.png )
### 6. RS256 проверка

HS256 использует общий секрет, который знают оба сервера и при утечке с одного этот секрет утекает. RS256 же использует ассиметричное шифрование, только сервер владеет приватным ключом, а наш фастапи только публичным

![08](screenshots/08-rs256-success.png )
![09d](screenshots/09-rs256-fail.png )
### 7. Полный CRUD с author_name

имя автора передается в payload, т.к клиент хранит данные об имени в комментариях и передает их там, т.к в процессе времени владелец может изменять свое имя и поэтому таким образом мы сохраняем историю, тем более это бизнес логика конкретного запроса

если зашить в JWT custom claim, то при смене имени сервер будет воспринимать его с тем именем, который был на момент выдачи JWT, и получается что в разных сессиях пользователь может быть с разными именами - неконсистетно получается


![10](screenshots/10-crud-all.png )
### 8. Owner check

проверка происходит в методах обновления и удаления коммента, если убрать проверку, то любой юзер сможет изменять или удалять абсолютно все комментарии


![11 ](screenshots/11-owner-check.png  )
### 9. CORS

allow_origins=['*'] + credentials=true браузер блокирует, потому что таким обрразом любой сайт мог бы отправлять запросы с куками и JWT. этим бы пользовались злодеи, создавая на сайте вредоносный код, который отправлял бы к нашему API запросы от имени пользователя и браузер автоматом бы прикреплял куку к такому запросу


![12](screenshots/12-cors-config.png )
### 10. PKCE утилиты

code_challenge это хэш от code_verifier, поэтому его утечка ничем не опасна. при обмене кода клиент отправит code_verifier и сервер проверяет, что его хэш совпадает с code_challenge. если перепутать и отправить code_verifier на /authorize, то злодей может его украсть и использовать

![13 ](screenshots/13-pkce-utils.png )
### 11. Login flow

![л14](screenshots/14-login-redirect.png)
![л15.](screenshots/15-login-callback.png)
### 12. Обмен code на токены

без state злодей сможет подменить code в нашей ссылке, state гарантирует, что code это точно от того запроса, который мы инициировали, что защищает от CSRF атаки

![16](screenshots/16-token-exchange.png )
### 13. Refresh token в HttpOnly cookie

при XSS злодей сможет прочитать рефреш из localstorage и использовать его для получения новых access, httponly это исправляет

![17](screenshots/17-refresh-cookie.png )
### 14. Silent refresh

![18](screenshots/18-silent-refresh.png )
### 15. Redis установлен

![19](screenshots/19-redis-ping.png )
### 16. Laravel publish new_post

redis лучше тем, что он работает асинхронно и в случае чего не блокирует ларавель и позволяет взаимодействовать нескольким сервисам, если нужно

![20 ](screenshots/20-laravel-publish.png )
### 17. FastAPI subscriber на new_post

![21 ](screenshots/21-subscriber-running.png  )
![22](screenshots/22-broadcast-flow.png )
### 18. User observer и user.renamed

UserObserver вызывается автоматом, потому что существует система событий Eloquent. Laravel создает событие updated при сохранении модели и наш обзервер его перехватывает


![23](screenshots/23-user-renamed.png )
### 19. Денормализация имени

Eventual consistency это промежуток времени, когда данные неконсистетны между публикацией события и обновлением комментариев

![24 ](screenshots/24-denorm-before.png )
![25 ](screenshots/25-denorm-after.png )
### 20. Два браузера: посты в реалтайме

![26 ](screenshots/26-two-browsers-post.png )
### 21. Два браузера: комментарии в реалтайме

![с27 ](screenshots/27-two-browsers-comment.png )

### 22. Никаких прямых HTTP-вызовов

![в коде нет Http::post() к FastAPI](screenshots/28-no-http-callback.png  )
![29L](screenshots/29-nginx-no-internal.png  )
