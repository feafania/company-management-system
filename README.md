Company Management System

A web application built with Laravel for managing companies and their employees. The project demonstrates CRUD operations, database relationships, form validation, file uploads and pagination.

Features

Company Management

* Create, view, edit and delete companies.
* Store company details, including name, email, address and website.
* Upload and display company logos.
* Validate form input.

Employee Management

* Create, view, edit and delete employees.
* Associate employees with companies.
* Display employee records with pagination.
* Validate employee details.

Additional Features

* Responsive interface using Tailwind CSS and Bootstrap.
* Database migrations, factories and seeders.
* Eloquent ORM for database operations.
* Reusable Blade templates and layout components.
* Laravel routing and request validation.

Technologies Used

* Backend: PHP, Laravel
* Frontend: Blade, HTML, Tailwind CSS, Bootstrap
* Database: MySQL / MariaDB
* Build Tools: Vite, npm
* Version Control: Git, GitHub

Installation

Prerequisites

Make sure you have the following installed:

* PHP (compatible with the Laravel version)
* Composer
* Node.js and npm
* MySQL or MariaDB

Setup

1. Clone the repository:

git clone https://github.com/feafania/company-management-system.git
cd company-management-system

2. Install PHP dependencies:

composer install

3. Install frontend dependencies:

npm install

4. Create your environment configuration:

cp .env.example .env

5. Generate the application key:

php artisan key:generate

6. Configure your database connection in .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=company_management
DB_USERNAME=your_username
DB_PASSWORD=your_password

7. Run database migrations:

php artisan migrate

8. (Optional) Populate the database with sample data:

php artisan db:seed

9. Create the public storage link for uploaded files:

php artisan storage:link

10. Build frontend assets:

npm run build

11. Start the development server:

php artisan serve

Open the local URL displayed in the terminal to access the application.

Development

To run the frontend development server:

npm run dev

To clear Laravel’s cached configuration, routes and views:

php artisan optimize:clear

Project Structure

app/
├── Http/Controllers/    # Application controllers
├── Models/              # Eloquent models
database/
├── factories/            # Model factories
├── migrations/           # Database schema
└── seeders/              # Sample data
resources/
└── views/                # Blade templates
routes/
└── web.php               # Web routes
storage/
└── app/public/           # Uploaded files
public/
└── build/                # Compiled frontend assets

Purpose

This project was developed as part of my software development training to practise building a Laravel application with database-backed CRUD functionality, file uploads, validation and relationships between models.

Author

Tatsiana Kashko

* GitHub: feafania
