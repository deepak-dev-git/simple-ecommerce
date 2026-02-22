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

    ```bash
    cd simple-ecommerce
    ```

3. Install dependencies:

    ```bash
    composer install
    ```

4. Copy environment file:

    ```bash
    cp .env.example .env
    ```

5. Generate application key:

    ```bash
    php artisan key:generate
    ```

6. Configure database in `.env`

7. Run storage link, migrations, and seeders:

    ```bash
    php artisan storage:link
    php artisan migrate
    php artisan db:seed
    ```

    The seeder includes:
    - Admin & customer accounts
    - Dummy products
    - Sample orders

8. Start the server:

    ```bash
    php artisan serve
    ```

9. Login Credentials:

    **Admin**
    - Email: admin@gmail.com
    - Password: password

    **Customer**
    - Email: user@gmail.com
    - Password: password

10. Frontend assets are pre-built.  
    If you encounter any asset-related issues, run:

    ```bash
    npm install
    npm run build
    ```

---

## 🔐 Default Setup

Make sure your database is created before running migrations.

---

## 👨‍💻 Author

Your Name  : Deepak M
GitHub: https://github.com/deepak-dev-git
