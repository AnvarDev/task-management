## Locally deployment

**Required:** in your development environment you have PHP 8.x, Composer, Node.js and NPM installed.

1. To install all the packages run the command:
```
composer install && npm install --legacy-peer-deps
```
2. Rename `.env.example` to `.env` in the root folder and change the `APP_URL` to `http://localhost:8000`
3. To generate a new application key run the command:
```
php artisan key:generate
```
4. To create a new DB and all thetables in it run the command:
```
php artisan migrate
```
5. To run all tests use the command (**Optional**):
```
php artisan test
```
6. To create default records in the DB run the command:
```
php artisan db:seed
```
7. To create the symbolic link run the command:
```
php artisan storage:link
```
8. To access the Laravel application run a local development server by the command:
```
composer run dev
```

## Admin dashboard

The system automatically creates a user for manually tests, there's the credentials:
```
login: admin@test.com
password: admin
```
Use the [link](http://localhost:8000/admin/login) to get access to the admin dashboard.


## API documentation

API documentation generated in Postman. It contains tutorials and responses examples. Import all collections to your Postman and run the requests in a specified order to test the functionality of API.

https://documenter.getpostman.com/view/8520996/2sAYkGLf4k