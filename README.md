## Project Overview

ChicAura Supply Chain System is a comprehensive Laravel-based application that manages the complete supply chain flow from suppliers through manufacturers to wholesalers, with an integrated public-facing e-commerce storefront. The system includes user management, inventory control, production planning, quality assurance, financial tracking, customer management, and shopping cart functionality, with integrated machine learning models for demand forecasting.

## Architecture

### Core Components

- **SupplyChain/**: Main Laravel application with multi-role authentication system
- **ml_models/**: Python-based machine learning models for demand forecasting using Prophet
- **SoftwareProject/**: Legacy project structure (not actively used)

### Key Directories

- `SupplyChain/app/Models/`: Eloquent models for all business entities
- `SupplyChain/app/Http/Controllers/`: Controllers organized by user roles (Admin, Supplier, Manufacturer, Wholesaler)
- `SupplyChain/resources/views/`: Blade templates organized by user roles
- `SupplyChain/database/migrations/`: Database schema definitions
- `SupplyChain/database/seeders/`: Database seeders for test data
- `ml_models/`: Python scripts for demand forecasting and analytics

## Common Commands

### Laravel Development (SupplyChain directory)

```bash
# Development server
php artisan serve

# Database operations
php artisan migrate
php artisan migrate:rollback
php artisan db:seed
php artisan migrate:fresh --seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Create models, controllers, migrations
php artisan make:model ModelName -mcr
php artisan make:controller ControllerName
php artisan make:migration create_table_name

# Queue management
php artisan queue:work
php artisan queue:restart

# Testing
php artisan test
./vendor/bin/phpunit
```

### Frontend Asset Management

```bash
# Install dependencies
npm install

# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

### Machine Learning Models (ml_models directory)

```bash
# Install Python dependencies
pip install -r requirements.txt

# Train demand forecasting model
python demand_model.py

# Generate forecast plots
python forecast_and_plot_daily.py
python forecast_and_plot_monthly.py

# Run specific analysis
python actual_vs_predicted_plot_daily.py
python actual_vs_predicted_plot_monthly.py
```

## Data Models and Relationships

### User Management
- **User**: Central user model with role-based access (admin, supplier, manufacturer, wholesaler)
- **Customer**: Public-facing customers for e-commerce functionality with demographic data
- **PendingUsers**: Stores registration requests before approval
- **Supplier/Manufacturer/Wholesaler**: Role-specific business entity models

### Supply Chain Flow
- **Item**: Product catalog (raw materials and finished products)
- **SupplyRequest**: Requests from manufacturers to suppliers
- **SuppliedItem**: Actual deliveries from suppliers
- **PriceNegotiation**: Price negotiation between parties
- **Order/OrderItem**: Customer orders from wholesalers to manufacturers
- **CustomerOrder/CustomerOrderItem**: Public customer orders with order management
- **Invoice**: Financial documentation

### Production Management
- **WorkOrder**: Production instructions and tracking
- **BillOfMaterial/BillOfMaterialComponent**: Product composition definitions
- **ProductionSchedule**: Time-based production planning
- **QualityCheck**: Quality control at various stages
- **ProductionCost**: Cost tracking for production
- **DowntimeLog**: Production interruption tracking

### Inventory and Workforce
- **Warehouse**: Physical storage locations
- **InventoryItem**: Actual inventory tracking
- **Workforce**: Employee management
- **WorkOrderAssignment**: Worker assignments to production tasks

### Communication
- **ChatMessage**: Built-in messaging system between stakeholders

## Machine Learning Integration

The system integrates Python-based machine learning models for demand forecasting:

- **Prophet Models**: Time series forecasting for demand prediction
- **Database Integration**: ML models connect to MySQL database for real-time data
- **Visualization**: Automated plot generation for forecast analysis
- **Frequency Support**: Both daily and monthly forecasting capabilities

### ML Model Files
- `demand_model.py`: Main Prophet-based demand forecasting model
- `db_config.py`: Database connection configuration
- `forecast_and_plot_*.py`: Forecast generation and visualization scripts
## Development Workflow

### Setting Up Development Environment

1. **Laravel Setup**:
   ```bash
   cd SupplyChain
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate:fresh --seed
   ```

2. **Frontend Setup**:
   ```bash
   npm install
   npm run dev
   ```

3. **ML Models Setup**:
   ```bash
   cd ml_models
   pip install -r requirements.txt
   python api_server.py
   python demand_model.py
   python monitoring.py
   ```

### Role-Based Development

The system has distinct interfaces for different user roles:

- **Admin**: User management, system analytics, role assignments
- **Supplier**: Supply request management, inventory listings, chat with manufacturers
- **Manufacturer**: Production management, workforce, warehouse, quality control, supplier communications
- **Wholesaler**: Order placement, invoice management, manufacturer communications
- **Customer**: Public e-commerce interface with registration, authentication, shopping cart, and order management

### Authentication Systems

The application uses multiple authentication guards:

- **Default Guard**: For internal users (admin, supplier, manufacturer, wholesaler)
- **Customer Guard**: Separate authentication system for public customers with distinct login/registration flows

### Public E-commerce Architecture

The system includes a complete public-facing e-commerce interface:

- **PublicController**: Handles product catalog, search, and product detail pages
- **CartController**: Manages session-based shopping cart with AJAX support
- **CustomerController**: Customer authentication and profile management
- **CustomerOrderController**: Order processing and management for public customers

**Key Routes:**
- `/`: Homepage with featured products
- `/products`: Product catalog with filtering and search
- `/products/{id}`: Individual product detail pages
- `/cart/*`: Shopping cart management (add, update, remove, checkout)
- `/customer/*`: Customer authentication and account management

### Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
./vendor/bin/phpunit --testsuite=Feature
./vendor/bin/phpunit --testsuite=Unit

# Run specific test file
./vendor/bin/phpunit tests/Feature/AuthenticationTest.php

# Run tests with coverage
./vendor/bin/phpunit --coverage-html reports/
```

- **PHPUnit Tests**: Located in `SupplyChain/tests/`
- **Feature Tests**: Test user authentication, registration, and core functionality
- **Unit Tests**: Test individual components and models

### Code Quality

```bash
# Laravel Pint (code formatting)
./vendor/bin/pint

# Laravel Pint with specific configuration
./vendor/bin/pint --config pint.json
```

### Database Schema

The system uses MySQL with comprehensive migrations covering:
- User authentication and roles
- Supply chain transactions
- Production management
- Inventory tracking
- Communication logs
- Financial records

## Key Features

- **Multi-tenant Role System**: Different interfaces for suppliers, manufacturers, and wholesalers
- **Public E-commerce Storefront**: Customer-facing product catalog with shopping cart functionality
- **Customer Management**: Registration, authentication, and profile management with demographic data collection
- **Shopping Cart**: Session-based cart with product variations (size, color), stock validation
- **Real-time Chat**: Built-in messaging between stakeholders
- **Production Planning**: Complete Bill of Materials and work order management
- **Quality Control**: Multi-stage quality checks and tracking
- **Inventory Management**: Warehouse and stock tracking
- **Financial Tracking**: Invoice generation and cost analysis
- **ML-Powered Forecasting**: Demand prediction using Prophet time series models
- **Automated Reporting**: Weekly reports and analytics
- **Document Management**: Support for business license and document uploads

## Environment Configuration

- **Database**: MySQL (configured in `SupplyChain/.env`)
- **Queue**: Redis or database driver for background jobs
- **Mail**: Configured for user notifications and reports
- **Storage**: File uploads for documents and images
- **Broadcasting**: Real-time features for chat system

## Security Considerations

- **Role-based Access Control**: Implemented through middleware and policies
- **Input Validation**: Form requests for data validation
- **Authentication**: Laravel Sanctum for API authentication
- **File Upload Security**: Validated file types and storage
- **SQL Injection Prevention**: Eloquent ORM and parameter binding
- **Multi-Guard Authentication**: Separate authentication systems for internal users and customers

## Development Guidelines

### Working Directory

All Laravel development work should be done from the `SupplyChain/` directory. When running commands, always ensure you are in the correct directory:

```bash
cd SupplyChain
php artisan serve
```

### Database Migrations

When working with the database, prefer using migrations over direct SQL modifications:

```bash
# Create new migration
php artisan make:migration description_of_change

# Apply migrations
php artisan migrate

# Reset and re-seed database for development
php artisan migrate:fresh --seed
```

### Customer vs User Models

Be aware of the dual authentication system:
- `User` model for internal stakeholders (admin, supplier, manufacturer, wholesaler)
- `Customer` model for public e-commerce customers
- Use appropriate guards when implementing authentication features

# important-instruction-reminders
Do what has been asked; nothing more, nothing less.
NEVER create files unless they're absolutely necessary for achieving your goal.
ALWAYS prefer editing an existing file to creating a new one.
NEVER proactively create documentation files (*.md) or README files. Only create documentation files if explicitly requested by the User.
