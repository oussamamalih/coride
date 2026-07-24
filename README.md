# Coride

Coride is a corporate carpooling and ride-sharing platform built with Laravel. It connects employees (Employés) from various companies (Entreprises) with drivers (Conducteurs) to share daily commutes and trips (Trajets), fostering a more sustainable and cost-effective way to travel.

## Features

- **User Authentication**: Secure login and registration powered by Laravel Breeze.
- **Role-based Dashboards**: Dedicated interfaces for Drivers (Conducteurs) and Employees/Passengers.
- **Trip Management (Trajets)**: Create, view, and manage ride-sharing trips.
- **Corporate Integration**: Manage companies (Entreprises) and their associated employees (Employés).

## Tech Stack

- **Framework**: Laravel 13.x
- **Language**: PHP ^8.3
- **Authentication**: Laravel Breeze
- **Frontend**: Blade, Tailwind CSS, Vite

## Getting Started

### Prerequisites

- PHP >= 8.3
- Composer
- Node.js & npm

### Installation

1. **Clone the repository** (if applicable)
   ```bash
   git clone <your-repository-url>
   cd coride
   ```

2. **Run the setup script**
   Coride includes a convenient setup script that handles dependencies, environment variables, migrations, and frontend assets:
   ```bash
   composer setup
   ```

   *Alternatively, you can run the installation commands manually:*
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   npm install
   npm run build
   ```
  
3. **Database Seeding (Optional)**
   To populate the database with initial dummy data (such as dummy Companies and Employees):
   ```bash
   php artisan db:seed
   ```


4. **Start the Development Server**
   Start the Laravel backend and Vite frontend server in a single command:
   ```bash
   composer dev
   ```
   The application will be available at `http://localhost:8000`.

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
