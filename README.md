## About

Our clients operate in real estate sector, managing multiple buildings within their accounts. We need to provide a tool that allows our owners to create tasks for their teams to perform within each building and add comments to their tasks for tracking progress. These tasks should be assignable to any team member and have statuses such as Open, In Progress, Completed and Rejected.

## Setting up the application

Execute the following steps to run the application successfully after cloning the project:

- Run the command to install all dependencies: **composer install**
- Create a **.env** file to set the environment variables, you can copy from .env.example
- Run the command **php artisan key:generate** to create the APP_KEY
- Run the command **php artisan jwt:secret** to create a secret key
- Connect to a local database server and set the environment variables as **DB_USERNAME and DB_PASSWORD**
- Create a database with the name given in **.env** by **DB_DATABASE**
- Run the command **php artisan migrate** to migrate all tables
- Run the command **php artisan db:seed**  to run the seeders

## Running

- Run the command **php artisan serve**  to run the application
- Check if the [Welcome Page](http://localhost:8000) opens.

## API

**URL Base**: http://localhost:8000/api

**Test User**

Email: test@test.com
Password: test123

You can found a file to import the requests into Insomnia in /public/utils/Insomnia_TaskManager