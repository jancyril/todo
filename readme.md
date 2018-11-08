# Simple ToDo Application

## Installation

### Option 1
- Clone this repository
- Run `composer install`
- Rename `.env.example` to `.env` and add your database name, user and password
- Run `php artisan migrate`
- Open your browser

### Option 2
- Ensure you have docker and docker-compose installed
- Clone this repository
- Rename `.env.example` to `.env`
- Run `docker-compose up -d`
- Get the container user's group id by running `docker exec todo_php_1 id`, note that the word `todo` varies, change this to the folder where you save this repository, this is necessary to set proper file permission.
- After getting the group-id, chown and chmod this repository by running `chown -R yourusername:{idNumber} .` and `chmod -R ug+rw .`
Note: Change `{idNumber}` to whatever value you get from the previous command.
- Migrate the database, `docker exec todo_php_1 php artisan migrate`
- Open your browser and go to `http://localhost`
- To access the phpmyadmin, go to `http://localhost:8080`

## Resources
- PNotify - https://sciactive.com/pnotify/
- Sweetalert - https://sweetalert.js.org/guides/
- Boostrap Template - https://startbootstrap.com/template-overviews/bare/
- Laravel Activity Log - https://docs.spatie.be/laravel-activitylog/v3/
