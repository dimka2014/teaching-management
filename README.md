# teaching-management
Simple managing application for teachers

## To install
### install project
```
php composer.phar install 
```
### create database if it's not exist
```
php app/console doctrine:database:create 
```
### create database schema
```
php app/console doctrine:schema:create 
```
### load admin data
```
php app/console doctrine:fixtures:load 
```
### start php server
```
php -S localhost:8000 -t web/
```
### site will be available on localhost:8000/app_dev.php
