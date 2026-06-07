# Отчёт к лабораторной работе №5 Семёнов В.А
## HTTPS
### 1. Установка certbot
![вывод certbot --version](screenshots/01-certbot-installed.png)
---
### 2. Получение сертификата
![вывод certbot ](screenshots/02-certbot-success.png)
---
### 3. Проверка в браузере
![браузер с замочком ](screenshots/03-browser-lock.png)
![Info of certificate](screenshots/04-certificate-info.png)
---
### 4. Редирект
![curl](screenshots/05-redirect.png)
---
### 5. Конфиг после certbot
![конфиг с подписями](screenshots/06-nginx-ssl-config.png)
---
### 6. Сертификат для api-поддомена
![Success](screenshots/07-api-certbot.png)
---
### 7. Проверка обоих доменов
![200 with headers](screenshots/08-both-https.png)
### 8. TLS handshake
![вывод с подписями](screenshots/09-tls-handshake.png)
### 9. Цепочка доверия
vyache.space → Let's Encrypt → ISRG Root X1
	Браузер проверяет с конца, сертф подписан, летс енкрипт подписан. Если во всех точках всё ок, то появляется замочек
  
![Вывод openssl](screenshots/10-chain.png)
### 10. Сравнение сертификатов
Исходя из вывода, трудно сказать, что именно у них общего, кроме даты выдачи и даты истечения срока. Разные -- параметр subject, который указывает на домен, которому принадлежит сертификат. Поэтому можно сказать, что сертификаты разные и выпущены отдельно на vyache.space & api.vyache.space

![вывод обоих доменов](screenshots/11-compare-certs.png)
### 11. HSTS
Браузер запомнит, что подключение к данному домену только по HTTPS, поэтому во все последующие разы он будет исправлять любой запрос к этому домену, заменяя протокол на Https
![заголовок Strict-Transport-Security](screenshots/12-hsts.png)
### 12. Кэширование и gzip
![вывод обоих заголовков ](screenshots/13-cache-gzip.png )
### 13. Автообновление
![Congratulations, all simulated renewals succeeded](screenshots/14-renew.png)
### 15. PR
![PR](screenshots/15-PR.png)
