## User Management System API

Welcome to User Management System API. This API helps the admin to manage users, it also help users register and modify their acount. it also give admin the ability to modify and delete a user account

### Environmental Requirement

-   PHP ^7.2|^8.0
-   composer

### Installation

-   run "composer install"
-   run "copy .env.example .env"
-   run "copy .env.example .env.test" (Set up testing env)

### Set up for laraval passport authenticator

-   run "php artisan migrate"
-   run "php artisan passport:client --personal"

After creating your personal access client, place the client's ID and plain-text secret value in your application's .env file:

PASSPORT_PERSONAL_ACCESS_CLIENT_ID="client-id-value"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="unhashed-client-secret-value"

### Generate singning keys

- run "php artisan passport:keys"

### Generate App keys
 
- run "php artisan key:generate"

### Register Admin

- run "php artisan db:seed --class=RegisterAdminSeeder"

 NOTE: admin email is "admin@gmail.com", while password is "password"

 ### Run Test

 - run "php artisan test tests/Feature


### Postman test link

- https://documenter.getpostman.com/view/23178216/2sA2xfZZPu