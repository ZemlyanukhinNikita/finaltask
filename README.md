## Приложение для создания отложенных транзакций между пользователями

# Описание задачи 

Используя любой PHP-фреймворк создать приложение, которое имеет следующие возможности:   
Любой пользователь приложения может выбрать любого другого пользователя приложения (кроме себя), чтобы сделать отложенный перевод денежных средств со своего счета на счет выбранного пользователя.  

При планировании такого перевода пользователь указывает сумму перевода в рублях, дату и время, когда нужно произвести перевод. Сумма перевода ограничена балансом клиента на момент планирования перевода с учетом ранее запланированных и невыполненных его исходящих переводов. 

Дата и время выбирается с точностью до часа с использованием календаря.  

Способ выбора пользователя - любой (можно просто ввод ID).  

Ввод данных должен валидироваться как на стороне клиента, так и на стороне сервера с выводом ошибок пользователю.  

Показать на сайте список всех пользователей и информацию об их одном последнем переводе с помощью одного SQL-запроса к БД. 

Реализовать сам процесс выполнения запланированных переводов. Не допустить ситуации, при которой у какого-либо пользователя окажется отрицательный баланс.  

Написанный для решения задачи код не должен содержать уязвимостей. 

Процесс регистрации и проверки прав доступа можно не реализовывать. Для этого допустимо добавить дополнительное поле ввода для указания текущего пользователя.  

Внешний вид страниц значения не имеет.  

 Решение задачи должно содержать:
 - Весь текст поставленного тестового задания. 
 - Четкую инструкцию по развертыванию проекта с целью проверки его работоспособности. Приветствуется использование Docker. 
 - Миграции и сиды для наполнения БД демонстрационными данными.

Для  начала работы необходимо:
 - Php 7.1
 - Mysql 5.7
 - docker
 - composer

# Разворачивание проекта с помощью Docker: 
```
 1. Выполнить команду git clone https://github.com/ZemlyanukhinNikita/finaltask.git
 2. Выполнить команду cd finaltask
 3. Выполнить команду cp .env.example .env 
 4. Открыть файл docker-compose.yml и установить переменные (можно оставить по умолчанию)
 - "MYSQL_DATABASE="
 - "MYSQL_USER="
 - "MYSQL_PASSWORD="
 - "MYSQL_ROOT_PASSWORD="
 5. Открыть файл env и установить переменные (должно совпадать с данными в docker-compose.yml)
 - DB_DATABASE=
 - DB_USERNAME=
 - DB_PASSWORD=
 6. Выполнить команду make run
 ```
 # Разворачивание проекта без Docker  
```
 1. Выполнить команду git clone https://github.com/ZemlyanukhinNikita/finaltask.git
 2. Выполнить команду cd finaltask
 3. Выполнить команду cp .env.example .env 
 4. Открыть файл env и отредактировать переменые
 - DB_DATABASE=
 - DB_USERNAME=
 - DB_PASSWORD=
 - DB_PORT:3306
 5. Выполнить команду composer install
 6. Выполнить команду php artisan serve
 
 ```
 ## Для запуска отложеных платежей необходимо выполнить следующие действия
 ```
 Выполнить команду crontab -e  
 Дописать в конец файла строку
 
 * * * * * docker exec finaltask_app_1 php artisan schedule:run >> /dev/null 2>&1
 ```
 
 ## Для запуска тестов введите команду 
 
 ``` docker exec finaltask_app_1 vendor/bin/phpunit ```
