# Sembark URL Shortener Assignment

This is a **Laravel 11** based URL Shortener application with multi-tenancy (SuperAdmin, Admin, Member roles) and separate workspaces for companies.

## Prerequisites
- PHP >= 8.2
- Composer
- SQLite 

## Local Setup Instructions

Follow these steps to set up the project locally for testing:

1.  **Clone or Extract** the project to your local machine.

2.  **Install Dependencies**:
    ```bash
    composer install
    ```

3.  **Environment Setup**:
    - Copy the example environment file:
      ```bash
      cp .env.example .env
      ```
    - Generate the application key:
      ```bash
      php artisan key:generate
      ```

4.  **Database Setup (SQLite)**:
    - Ensure the SQLite database file exists.
    - **Windows (PowerShell)**:
      ```powershell
      New-Item -ItemType File -Force database/database.sqlite
      ```
    - **Linux/Mac/Git Bash**:
      ```bash
      touch database/database.sqlite
      ```
    - Run migrations and seed the database (creates default Super Admin):
      ```bash
      php artisan migrate:fresh --seed
      ```

5.  **Run the Application**:
    ```bash
    php artisan serve
    ```
    Access the app at: [http://localhost:8000/login](http://localhost:8000/login/)

## Credentials (For Testing)

The database seeder creates a default **Super Admin** account:

-   **Email**: 'super@example.com'
-   **Password**: 'password'

You can use this account to:
1.  Invite new **Clients** (Company Admins).
2.  Log in as those Clients to invite **Team Members**.
3.  Generate and Manage Short URLs.

## Running Tests

To run the automated feature tests (verifying Access Control and Logic):

```bash
php artisan test
```

## Acknowledgments

This project was developed with **Google Gemini**, which was utilized to:
1.  Define the initial **Architecture** of the application.
2.  Assist in modularizing the logic errors.
3. knowing the advanced module of Laravel like middleware, controllers and routes.
