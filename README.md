# Appointment Management - V2.0

This is an appointment management project developed with the Laravel framework.

## Requirements

- PHP >= 8.0
- Composer
- Node.js and npm
- Database (MySQL, PostgreSQL, SQLite, etc.)

## Installation

1. Clone the repository:

    ```sh
    git clone https://github.com/Darkot1/Assessment-GestionCitas-V2.0.git
    ```

2. Install PHP dependencies:

    ```sh
    composer install
    ```

3. Install Node.js dependencies:

    ```sh
    npm install
    ```

4. Copy the environment file and configure the variables:

    ```sh
    cp .env.example .env
    ```

    Open the `.env` file and configure the environment variables, especially the database configuration.

5. Generate the application key:

    ```sh
    php artisan key:generate
    ```

6. Run the migrations and seeders to set up the database:

    ```sh
    php artisan migrate --seed
    ```

7. Compile the frontend assets:

    ```sh
    npm run dev
    ```

## Usage

To start the development server, run:

    ```sh
    php artisan serve
    ```

Then, open your browser and go to http://localhost:8000.

## Default Users

The system comes with three pre-configured users for testing:

### Administrator
- Email: admin@example.com
- Password: password
- Role: Administrator

### Doctor
- Email: doctor@example.com
- Password: password
- Role: Doctor
- Specialty: Cardiology

### Patient
- Email: patient@example.com
- Password: password
- Role: Patient

## Features by Role

### Administrator
- Full management of users (doctors and patients)
- Management of medical appointments
- Management of doctors' schedules
- Access to medical records
- Management of doctors' availability
- Reports and statistics

### Doctor
- View and manage their appointment schedule
- Create and modify working hours
- Mark appointments as completed
- Cancel scheduled time slots
- View and update patient medical records
- Manage their availability

### Patient
- Schedule new medical appointments
- View their scheduled appointments
- Access their personal medical record
- Cancel or reschedule appointments
- View doctors' availability

## Tests

To run the tests, use the following command:

    ```sh
    php artisan test
    ```

## Project Structure

- app/: Contains models, controllers, and other application files.
- config/: Contains configuration files.
- database/: Contains migrations, seeders, and factories.
- public/: Contains public files like index.php.
- resources/: Contains views and frontend files.
- routes/: Contains route files.
- storage/: Contains files generated by the application.
- tests/: Contains tests.
