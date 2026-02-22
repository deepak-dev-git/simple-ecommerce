# Simple E-Commerce

A simple e-commerce built with Laravel.

## 🚀 Features

- Product listing
- Product details page
- Add to cart
- Order management
- Admin/customer status management

---

## 🛠️ Tech Stack

- PHP (Laravel)
- MySQL
- Blade
- Vite
- Bootstrap

---

## 📦 Installation Guide

1. Clone the repository:

   git clone https://github.com/deepak-dev-git/simple-ecommerce.git

2. Navigate into the project:

   cd simple-ecommerce

3. Install dependencies:

   composer install

4. Copy environment file:

   cp .env.example .env

5. Generate application key:

   php artisan key:generate

6. Configure database in `.env`

7. Run storage link, migrations, Seeders:

   php artisan storage:link
   php artisan migrate
   php artisan db:seed
   (The seeder includes admin & customer accounts, dummy products, and sample orders.)

8. Start the server:

   php artisan serve

9. Login Credentials:

   Admin:
   Email: admin@gmail.com
   Password: password

   Customer:
   Email: user@gmail.com
   Password: password

10. Frontend assets are pre-built.  
   If you encounter any asset-related issues, run:

   npm install
   npm run build

---

## 🔐 Default Setup

Make sure your database is created before running migrations.

---

## 👨‍💻 Author

Your Name  : Deepak M
GitHub: https://github.com/deepak-dev-git
