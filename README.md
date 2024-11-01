## Installation

1. Clone this project
    ```bash
    git clone https://github.com/Vetencode-com/healthalert healthalert
    cd healthalert
    ```
2. Install dependencies

    ```bash
    composer install
    ```

And javascript dependencies
Note: Please run this step, because we use vite for managing asset

    ```bash
    yarn install && yarn dev

    #OR

    npm install && npm run dev
    ```

4. Set up Laravel configurations

    ```bash
    copy .env.example .env
    php artisan key:generate
    ```

5. Set your database in .env

6. Migrate database

    ```bash
    php artisan migrate --seed
    ```

7. Serve the application

    ```bash
    php artisan serve
    ```

