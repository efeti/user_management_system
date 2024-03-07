## User Management System API

Welcome to User Management System API. This API helps the admin to manage users, it also help users register and modify their acount. it also give admin the ability to modify and delete a user account

### Environmental Requirement

-   PHP ^7.2|^8.0
-   composer

### Installation

-   run "composer install"
-   run "cp .env.example .env"
-   run "cp .env.example .env.test" (Set up testing env)

### Set up for laraval passport authenticator

-   run "php artisan passport:client --personal"

-   run "run artisan migrate"

After creating your personal access client, place the client's ID and plain-text secret value in your application's .env file:

PASSPORT_PERSONAL_ACCESS_CLIENT_ID="client-id-value"
PASSPORT_PERSONAL_ACCESS_CLIENT_SECRET="unhashed-client-secret-value"

run php artisan passport:keys