#Установка

```
composer install
docker-compose up --build
```
#Настройка БД
```
docker-compose exec php-fpm ./bin/console doctrine:database:create
docker-compose exec php-fpm ./bin/console doctrine:migrations:migrate

# создание тестовых данных
docker-compose exec php-fpm ./bin/console doctrine:fixtures:load
```
#Swagger
```
/api/doc
/api/doc.json
```