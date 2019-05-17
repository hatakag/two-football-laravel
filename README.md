# Two-football Server
Using Laravel, MySQL, Pusher...

### Installation requirements
- Laravel (including `composer`)
- MySQL >= 8.0

### How to run
- Firstly, clone the project
- Change to project folder, run this command `composer install` to install all dependencies
- **For development:**
    - Create new MySQL user if not have, create new database called `two-football`
    - Configure the `.env` file, in the `DB` part to match with your user and database
    - Run the following command to create all the tables `php artisan migrate` (drop tables `php artisan migrate:reset`)
    - Create initial records on your tables by `php artisan db:seed` and to reset `php artisan migrate:refresh --seed`
    
- Run the server: `php artisan serve`

### Defined routes
To see all routes that are available, run `php artisan route:list`    
    
