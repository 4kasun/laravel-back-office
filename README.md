# laravel-back-office

This project is a back-office solution built with **Laravel** as the back end and a **Vue.js** + **Vuetify** front end. It is designed to integrate with a self-hosted or WordPress.com blog, allowing an administrator to manage blog posts and other content from a single, modern interface.

***

### ⚙️ **Features**

* **Secure Authentication**: A custom authentication system for logging in as a WordPress user.
* **Blog Post Management**: Full CRUD (Create, Read, Update, Delete) functionality for managing WordPress blog posts directly from the back-office.
* **Post Priority**: A unique feature to set and manage a display priority for posts, stored in the Laravel database.
* **Single-Page Application (SPA)**: A fast and responsive user experience powered by Vue and Vuetify.

***

### 🚀 **Installation & Setup**

Follow these steps to get the project running on your local machine.

1.  **Clone the Repository**
    ```bash
    git clone [https://github.com/your-username/laravel-back-office.git](https://github.com/your-username/laravel-back-office.git)
    cd laravel-back-office
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install JavaScript Dependencies**
    ```bash
    npm install
    ```

4.  **Configure Environment Variables**
    * Create a `.env` file by copying the `.env.example` file.
        ```bash
        cp .env.example .env
        ```
    * Generate a new application key.
        ```bash
        php artisan key:generate
        ```
    * Open the `.env` file and update the following details for your setup:
        * **Database**: `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
        * **WordPress API**:
            ```ini
            WP_LOGIN_URL="[https://your-blog.wordpress.com](https://your-blog.wordpress.com)"
            WP_PUBLIC_API_URL="[https://public-api.wordpress.com/wp/v2/sites/your-blog.wordpress.com](https://public-api.wordpress.com/wp/v2/sites/your-blog.wordpress.com)"
            WP_CLIENT_ID="YOUR_CLIENT_ID"
            WP_CLIENT_SECRET="YOUR_CLIENT_SECRET"
            ```

5.  **Run Database Migrations**
    ```bash
    php artisan migrate
    ```

6.  **Run the Project**
    Open two separate terminal windows in the project root.
    * In the first terminal, start the Laravel development server:
        ```bash
        php artisan serve
        ```
    * In the second terminal, start the Vite development server for the front end:
        ```bash
        npm run dev
        ```

***

### 🖥️ **Usage**

After running the project, open your web browser and navigate to `http://localhost:8000/login`. You can then log in using the credentials of a WordPress administrator from your blog to access the back-office.