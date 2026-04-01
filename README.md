# YNINetwork MLM E-commerce Platform

![Laravel](https://img.shields.io/badge/Laravel-8.75-red.svg)
![PHP](https://img.shields.io/badge/PHP-7.3%20%7C%208.0-blue.svg)
![License](https://img.shields.io/badge/License-Proprietary-yellow.svg)

A comprehensive, enterprise-level Multi-Level Marketing (MLM) integrated e-commerce platform built with Laravel. This system combines robust online retail capabilities with a sophisticated 3-level referral commission structure, complete inventory management, integrated accounting, and CRM features.

## Table of Contents

- [Overview](#overview)
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [Module Documentation](#module-documentation)
- [API Documentation](#api-documentation)
- [Configuration](#configuration)
- [Database Schema](#database-schema)
- [Authentication & Authorization](#authentication--authorization)
- [Payment Gateways](#payment-gateways)
- [Deployment](#deployment)
- [Development Guidelines](#development-guidelines)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Overview

**YNINetwork MLM E-commerce Platform** is a production-ready solution for businesses looking to combine traditional e-commerce with multi-level marketing strategies. The platform supports:

- Full-featured online shopping experience
- 3-level referral commission system with automatic payouts
- Comprehensive inventory and warehouse management
- Integrated accounting module (Chart of Accounts, Vouchers, Financial Reports)
- Customer Relationship Management (CRM) with ticketing system
- Point of Sale (POS) for in-store transactions
- Mobile app support via RESTful API
- Multi-language interface (English, Bengali)
- Multiple payment gateway integrations

### Target Use Cases

- E-commerce businesses with referral programs
- MLM companies selling physical products
- Retail chains with multi-location inventory
- Businesses requiring integrated accounting
- Companies with both online and offline sales channels

## Key Features

### 🛒 E-Commerce Module

#### Product Management

- **3-Level Category Hierarchy**: Categories → Subcategories → Child Categories
- **Product Variants**: Support for colors, sizes, models, and custom attributes
- **Bundle Products**: Create package deals with multiple products
- **Product Reviews & Ratings**: Customer feedback system with star ratings
- **Q&A System**: Product-specific question and answer functionality
- **Advanced Attributes**: Brands, flags, units, conditions, warranties, regions
- **Image Gallery**: Multiple images per product with featured image support
- **Stock Tracking**: Real-time inventory monitoring

#### Order Management

- **Complete Order Lifecycle**: Pending → Approved → Dispatch → In Transit → Delivered/Cancelled/Return
- **Multiple Delivery Methods**: Home Delivery, Store Pickup, POS Handover
- **Order Sources**: Web, Mobile App, POS, Social Media
- **Guest Checkout**: Purchase without registration
- **Order Tracking**: Real-time status updates for customers
- **Order History**: Complete purchase history with filters

#### Shopping Experience

- **Smart Cart System**:
  - Session-based cart for guests
  - Database-persisted cart for registered users
  - Cart abandonment recovery
- **Coupon System**: Percentage and fixed-amount discounts
- **Wishlist**: Save products for later
- **Multiple Addresses**: Shipping and billing address management
- **Saved Payment Methods**: Store credit/debit card information securely
- **Advanced Search**: Full-text search with live suggestions
- **Smart Filters**: Filter by brand, category, price range, storage, SIM type, condition, warranty, region
- **District-Based Shipping**: Automatic delivery charge calculation

#### Content Management

- **Dynamic Banners & Sliders**: Homepage carousel management
- **Blog System**: Categories, posts, and tags
- **FAQ Management**: Frequently asked questions
- **Testimonials**: Customer success stories
- **Custom Pages**: Create static pages (About Us, Contact, etc.)
- **Policies**: Privacy Policy, Shipping Policy, Return Policy
- **Outlet Management**: Physical store locations
- **Video Gallery**: Product and promotional videos

### 💰 MLM (Multi-Level Marketing) Module

#### Referral System

- **3-Level Commission Structure**: Earn from direct referrals and 2 downstream levels
- **Unique Referral Codes**: Each customer gets a unique referral link
- **Genealogy Tree**: Visual representation of referral hierarchy
- **Parent-Child Tracking**: Complete downline management

#### Commission Management

- **Automatic Calculation**: Commissions calculated automatically on order delivery
- **Configurable Percentages**: Set custom commission rates for each level (Level 1, 2, 3)
- **Commission Workflow**: Pending → Approved → Paid → Rejected
- **Commission Logs**: Complete audit trail of all commission activities
- **Idempotency**: Prevents duplicate commission entries

#### Digital Wallet System

- **Balance Tracking**: Real-time wallet balance for each user
- **Transaction History**: Complete log of credits and debits
- **Auto-Deposit**: Approved commissions automatically credited to wallet
- **Multiple Use Cases**: Store earnings, pay for orders, receive payouts

#### Payouts & Withdrawals

- **Withdrawal Requests**: Users request wallet fund withdrawals
- **Approval Workflow**: Admin review and approval system
- **Payment Processing**: Track withdrawal payments
- **Minimum Thresholds**: Set minimum withdrawal amounts
- **Withdrawal History**: Complete payout records

#### MLM Analytics & Reports

- **MLM Dashboard**: Key metrics at a glance
  - Total earnings
  - Pending commissions
  - Active referrals
  - Passive income trends
- **Top Earners Leaderboard**: Motivate users with rankings
- **Referral Activity Reports**: Track referral performance
- **Commission Reports**: Filter by date, status, referrer, level

### 📦 Inventory Module

#### Warehouse Management

- **Multi-Warehouse Support**: Manage multiple storage locations
- **Warehouse Rooms**: Organize warehouses into rooms
- **Storage Bins/Cartons**: Detailed location tracking
- **Stock Location Mapping**: Know exactly where each product is stored

#### Purchase Management

- **Purchase Orders**: Create and manage POs from suppliers
- **Purchase Quotations**: Request and compare supplier quotes
- **Purchase Charges**: Add shipping, handling, customs charges
- **Automatic Stock Updates**: Inventory updated on PO receipt

#### Supplier Management

- **Supplier Database**: Maintain supplier information
- **Supplier Types**: Categorize suppliers
- **Supplier Payments**: Track payments and outstanding balances
- **Supplier Performance**: Historical purchase data

#### Inventory Reports

- **Stock Reports**: Current inventory levels by warehouse
- **Low Stock Alerts**: Automatic notifications for reorder points
- **Product Movement**: Track stock in/out transactions
- **Inventory Valuation**: Calculate total stock value

### 👥 CRM (Customer Relationship Management) Module

#### Customer Management

- **Unified Customer Profiles**: 360-degree customer view
- **Customer Categories**: Segment customers (VIP, Regular, Wholesale, etc.)
- **Source Tracking**: Know where customers came from (Web, Social, Referral)
- **Activity Timeline**: Complete interaction history
- **Purchase History**: Order patterns and lifetime value

#### Contact Management

- **Contact Requests**: Website contact form submissions
- **Activity Logging**: Track all customer interactions
- **Follow-up Scheduling**: Set reminders for callbacks
- **Next Contact Date**: Automated follow-up tracking

#### Support System

- **Ticket Management**: Create and track support tickets
- **Ticket Messaging**: Conversation thread for each ticket
- **File Attachments**: Share images, documents in tickets
- **Status Workflow**: Open → In Progress → Resolved → Closed
- **Priority Levels**: High, Medium, Low priority
- **Admin Assignment**: Assign tickets to support staff

#### Marketing

- **Newsletter Subscriptions**: Email list management
- **Subscriber Segmentation**: Target specific customer groups
- **Email Campaign Integration**: Ready for email marketing tools

### 💼 Accounts Module

#### Chart of Accounts

- **Account Types**:
  - Assets (Cash, Bank, Inventory, Receivables)
  - Liabilities (Payables, Loans)
  - Equity (Capital, Retained Earnings)
  - Revenue (Sales, Commission Income)
  - Expenses (Operating, Administrative)
- **Account Groups**: Organize accounts into groups
- **Subsidiary Ledgers**: Detailed sub-accounts
- **Account Hierarchy**: Multi-level account structure

#### Voucher System

- **Payment Vouchers**: Record cash/bank payments
- **Receive Vouchers**: Record cash/bank receipts
- **Journal Vouchers**: Adjustments and non-cash transactions
- **Contra Vouchers**: Bank-to-bank transfers
- **Automated Entries**: Auto-generate vouchers from orders/purchases

#### Financial Management

- **Expense Tracking**: Record and categorize expenses
- **Expense Categories**: Custom expense types
- **Tax Management**: Tax calculation and reporting
- **Customer Payments**: Record payments from customers
- **Supplier Payments**: Track payments to suppliers
- **Money Transfers**: Inter-account transfers

#### Financial Reports

- **Ledger Reports**: Account-wise transaction details
- **Trial Balance**: Verify accounting accuracy
- **Income Statement**: Profit & Loss report
- **Balance Sheet**: Financial position snapshot
- **Cash Flow Statement**: Track cash movements
- **Account Balances**: Real-time account summaries

### 🏪 Point of Sale (POS)

- **In-Store Sales**: Quick checkout for walk-in customers
- **POS Order Processing**: Simplified order creation
- **Barcode Scanning**: Fast product lookup (ready for integration)
- **Cash Drawer Integration**: Ready for hardware integration
- **Receipt Printing**: Generate printable receipts
- **Sales Reports**: Daily POS sales summary

### 📱 Mobile App API

Complete RESTful API with **196+ endpoints** for mobile applications:

#### Authentication API

- User registration with email verification
- Login with JWT token generation
- Social login (Google, Facebook, GitHub OAuth)
- Password reset and forgot password
- Profile management

#### Product API

- Product listing with pagination
- Product details with reviews and Q&A
- Category-wise products
- Search with filters
- Related products
- New arrivals and featured products

#### Shopping API

- Add/update/remove cart items
- Apply coupon codes
- Checkout and order placement
- Multiple payment methods
- Order tracking
- Order history

#### User API

- Profile information and updates
- Address management (add/edit/delete)
- Saved payment cards
- Wishlist management
- Support ticket creation

#### Content API

- Homepage sliders and banners
- Blog posts and categories
- FAQs and testimonials
- Store outlets
- Terms and policies

### 🌐 Additional Features

#### Multi-Language Support

- **English** (default)
- **Bengali** (বাংলা)
- Easily extendable to additional languages
- Language switcher in UI

#### Communication

- **SMS Service**: Template-based SMS sending
- **SMS History**: Track all sent messages
- **Email Service**: Transactional emails (order confirmations, password resets)
- **Email Templates**: Customizable email designs
- **Push Notifications**: Firebase Cloud Messaging integration for mobile apps

#### Security Features

- **Multi-Guard Authentication**: Separate auth for Admin, Customer, API
- **Role-Based Access Control**: Granular permissions
- **Email Verification**: Verify user emails before activation
- **Password Hashing**: Bcrypt encryption
- **CSRF Protection**: Laravel's built-in CSRF tokens
- **SQL Injection Prevention**: Eloquent ORM with prepared statements
- **XSS Protection**: Blade template engine auto-escaping
- **Honeypot Spam Protection**: Protect forms from bots
- **API Rate Limiting**: Prevent API abuse

#### System Administration

- **Demo Mode**: Restrict destructive actions in demo environment
- **Backup System**:
  - Automated database backups (SQL dumps)
  - File backup (products, users, banners)
- **Cache Management**: Clear application, view, route caches
- **Activity Logging**: Track user actions
- **Error Logging**: Comprehensive error tracking
- **System Settings**: Configure site-wide settings from admin panel

## Technology Stack

### Backend

| Technology              | Version   | Purpose                          |
| ----------------------- | --------- | -------------------------------- |
| **Laravel**             | 8.75      | PHP Framework                    |
| **PHP**                 | 7.3 - 8.0 | Programming Language             |
| **MySQL**               | 5.7+      | Relational Database              |
| **Laravel Sanctum**     | -         | API Authentication (Token-based) |
| **Laravel UI**          | 3.4       | Authentication Scaffolding       |
| **Laravel Socialite**   | 5.9       | OAuth (Google, Facebook, GitHub) |
| **Intervention Image**  | -         | Image Processing & Manipulation  |
| **Maatwebsite Excel**   | -         | Excel Import/Export              |
| **Yajra DataTables**    | -         | Server-Side DataTables           |
| **Laravel FileManager** | -         | File Management Interface        |
| **SSLCommerz**          | -         | Payment Gateway (Bangladesh)     |
| **Spatie Honeypot**     | -         | Spam Protection                  |

### Frontend

| Technology      | Version | Purpose                                |
| --------------- | ------- | -------------------------------------- |
| **Bootstrap**   | 5.1.3   | CSS Framework                          |
| **Laravel Mix** | 6.x     | Asset Compilation                      |
| **Axios**       | -       | HTTP Client                            |
| **jQuery**      | -       | JavaScript Library (legacy components) |
| **SASS/SCSS**   | -       | CSS Preprocessor                       |
| **Blade**       | -       | Laravel Templating Engine              |

### Infrastructure

- **Web Server**: Apache (with mod_rewrite)
- **Queue Driver**: Database
- **Cache Driver**: File-based
- **Session Driver**: File-based
- **Mail**: SMTP (Gmail)
- **Push Notifications**: Firebase Cloud Messaging

### Development Tools

- **Composer** (PHP dependency management)
- **NPM** (JavaScript dependency management)
- **Git** (Version control)
- **Artisan** (Laravel CLI)

## System Requirements

### Server Requirements

- **PHP**: >= 7.3 (Recommended: PHP 8.0)
- **Web Server**: Apache 2.4+ or Nginx
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Composer**: Latest version
- **Node.js**: 14.x or higher (for asset compilation)
- **NPM**: 6.x or higher

### PHP Extensions Required

```
- OpenSSL
- PDO
- Mbstring
- Tokenizer
- XML
- Ctype
- JSON
- BCMath
- Fileinfo
- GD or Imagick (for image processing)
- Zip
```

### Recommended Server Configuration

- **Memory**: Minimum 512MB RAM (Recommended: 2GB+)
- **Disk Space**: 2GB+ for application and uploads
- **PHP Memory Limit**: 256M or higher
- **Max Execution Time**: 300 seconds
- **Upload Max Filesize**: 50M or higher

### Local Development

- **Laravel Valet** (macOS)
- **Laravel Homestead** (Cross-platform)
- **XAMPP** / **WAMP** / **MAMP**
- **Docker** with Laravel Sail

## Installation

### Step 1: Clone the Repository

```bash
git clone <repository-url> tp-ecommerce
cd tp-ecommerce
```

### Step 2: Install PHP Dependencies

```bash
composer install
composer dump-autoload -o
```

### Step 3: Install JavaScript Dependencies

```bash
npm install
```

### Step 4: Environment Configuration

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Edit `.env` file with your configuration:

```env
APP_NAME=YNINetwork
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yninetwo_mlm_ecom
DB_USERNAME=root
DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateway (SSLCommerz)
SSLCZ_STORE_ID=your_store_id
SSLCZ_STORE_PASSWORD=your_store_password
SSLCZ_TESTMODE=true

# Social Login
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URL=http://localhost/auth/google/callback

# Firebase (Push Notifications)
FIREBASE_CREDENTIALS=path/to/firebase-credentials.json
```

### Step 5: Create Database

Create a MySQL database:

```sql
CREATE DATABASE yninetwo_mlm_ecom CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 6: Run Migrations

This project uses a custom migration command for modular architecture:

```bash
# Run all migrations (core + modules)
php artisan migrate:modules --force

# Fresh migration with seeding (for initial setup)
php artisan migrate:modules --fresh --force --seed-all
```

**Migration Command Options:**

- `--force`: Bypass confirmation prompts
- `--fresh`: Drop all tables and re-run migrations (DESTRUCTIVE)
- `--seed-all`: Run all seeders after migrations
- `--no-core`: Skip core migrations, run only module migrations
- `--module=NAME`: Run migrations for a specific module (e.g., `--module=ECOMMERCE`)
- `--pretend`: Show SQL without executing (dry-run)

### Step 7: Create Storage Link

```bash
php artisan storage:link
```

### Step 8: Set Permissions

```bash
# Linux/macOS
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set proper ownership
chown -R www-data:www-data storage bootstrap/cache
```

### Step 9: Compile Assets

```bash
# Development
npm run dev

# Production
npm run production

# Watch for changes (development)
npm run watch
```

### Step 10: Clear Caches

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 11: Start Development Server

```bash
php artisan serve
```

Access the application at `http://localhost:8000`

### Default Admin Access

After running seeders, you can log in with:

**Admin Panel**: `/admin/login`

- Check database `users` table for seeded admin credentials
- Or create admin manually via Tinker

## Project Structure

```
/media/ehsan/TP-IT-Archive/Ehsan/techparkit/TP-Ecommerce/
├── app/
│   ├── Console/              # Artisan commands
│   │   └── Commands/
│   │       └── MigrateModules.php  # Custom module migration command
│   ├── Exceptions/           # Exception handlers
│   ├── Http/
│   │   ├── Controllers/      # Base controllers
│   │   │   ├── Account/      # Account module controllers
│   │   │   ├── Api/          # API controllers
│   │   │   └── Tenant/       # Tenant controllers
│   │   └── Middleware/       # Custom middleware
│   │       ├── CheckUserType.php
│   │       ├── DemoMode.php
│   │       ├── SetLocale.php
│   │       └── TrackUserActivity.php
│   ├── Mail/                 # Email templates
│   ├── Models/               # Core models
│   │   ├── Cart.php
│   │   ├── PaymentGateway.php
│   │   ├── Region.php
│   │   └── Sim.php
│   ├── Modules/              # *** MODULAR ARCHITECTURE ***
│   │   ├── ACCOUNTS/         # Accounting & financial management
│   │   │   └── Managements/
│   │   │       ├── AccountGroups/
│   │   │       ├── Accounts/
│   │   │       ├── AccountTypes/
│   │   │       ├── Expenses/
│   │   │       ├── Transactions/
│   │   │       └── Vouchers/
│   │   ├── CRM/              # Customer relationship management
│   │   │   └── Managements/
│   │   │       ├── Contacts/
│   │   │       ├── CustomerCategories/
│   │   │       ├── Customers/
│   │   │       ├── Newsletter/
│   │   │       └── Support/
│   │   ├── ECOMMERCE/        # Core e-commerce functionality
│   │   │   └── Managements/
│   │   │       ├── Banners/
│   │   │       ├── Blogs/
│   │   │       ├── Categories/
│   │   │       ├── Coupons/
│   │   │       ├── FAQs/
│   │   │       ├── Orders/
│   │   │       ├── Outlets/
│   │   │       ├── Pages/
│   │   │       ├── ProductManagements/
│   │   │       │   ├── Brands/
│   │   │       │   ├── Products/
│   │   │       │   ├── ProductAttributes/
│   │   │       │   ├── ProductReviews/
│   │   │       │   └── ProductVariants/
│   │   │       ├── Settings/
│   │   │       ├── Testimonials/
│   │   │       └── UserManagements/
│   │   │           ├── Users/
│   │   │           ├── Addresses/
│   │   │           └── Cards/
│   │   ├── INVENTORY/        # Stock & warehouse management
│   │   │   └── Managements/
│   │   │       ├── Purchases/
│   │   │       ├── Stocks/
│   │   │       ├── Suppliers/
│   │   │       └── Warehouses/
│   │   └── MLM/              # Multi-level marketing features
│   │       ├── Database/
│   │       │   ├── Migrations/
│   │       │   └── Seeders/
│   │       ├── Managements/
│   │       │   ├── Commissions/
│   │       │   └── Withdrawals/
│   │       ├── Observers/
│   │       │   └── OrderObserverForMLM.php
│   │       └── Service/
│   │           ├── MlmCommissionService.php
│   │           └── ReferralTreeService.php
│   ├── Providers/            # Service providers
│   ├── Rules/                # Custom validation rules
│   └── Services/             # Business logic services
├── bootstrap/
├── config/                   # Laravel configuration
│   ├── auth.php              # Multi-guard authentication
│   ├── database.php
│   ├── filesystems.php
│   └── ...
├── database/
│   ├── migrations/           # Core migrations
│   └── seeders/              # Database seeders
├── public/                   # Public assets (web root)
│   ├── uploads/              # User-uploaded files
│   │   ├── products/
│   │   ├── banners/
│   │   ├── users/
│   │   └── ...
│   ├── tenant/               # Tenant-specific assets
│   ├── index.php             # Application entry point
│   └── ...
├── resources/
│   ├── css/
│   ├── js/
│   ├── lang/                 # Multi-language files
│   │   ├── en/               # English
│   │   └── bn/               # Bengali
│   ├── sass/                 # SCSS files
│   └── views/
│       ├── backend/          # Admin panel views
│       ├── tenant/
│       │   ├── admin/        # Tenant admin interface
│       │   └── frontend/     # Customer-facing views
│       │       ├── layouts/
│       │       ├── pages/
│       │       ├── products/
│       │       ├── cart/
│       │       ├── checkout/
│       │       └── account/
│       └── errors/           # Error pages (404, 500, etc.)
├── routes/
│   ├── web.php               # Main web routes
│   ├── api.php               # API routes (196+ endpoints)
│   ├── tenant_admin.php      # Admin panel routes
│   ├── tenant_frontend.php   # Frontend routes
│   ├── paymentRoutes.php     # Payment gateway routes
│   └── generalRoutes.php     # Backup & SMS routes
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/
├── .env                      # Environment configuration
├── .env.example              # Environment template
├── composer.json             # PHP dependencies
├── package.json              # JavaScript dependencies
├── webpack.mix.js            # Laravel Mix configuration
└── README.md                 # This file
```

### Module Structure

Each module follows a consistent structure:

```
app/Modules/MODULE_NAME/
├── Database/
│   ├── Migrations/           # Module-specific migrations
│   └── Seeders/              # Module-specific seeders
├── Managements/              # Business logic organized by feature
│   └── FeatureName/
│       ├── Controllers/      # HTTP controllers
│       ├── Database/
│       │   └── Models/       # Eloquent models
│       ├── Requests/         # Form request validation
│       ├── Resources/        # API resources
│       └── Services/         # Business logic services
├── Observers/                # Eloquent observers
└── Service/                  # Shared module services
```

## Module Documentation

### ECOMMERCE Module

**Location**: `app/Modules/ECOMMERCE/`

**Purpose**: Core online shopping functionality

**Key Features**:

- Product catalog management
- Order processing and lifecycle
- Category and attribute management
- User management (customers, admins, delivery personnel)
- Coupon and promotion system
- Content management (FAQs, blogs, banners)

**Main Models**:

- `User` - All user types with referral relationships
- `Product` - Products with variants and packages
- `Order` - Complete order management
- `Category`, `SubCategory`, `ChildCategory`
- `ProductReview`, `ProductQuestion`
- `Coupon`, `Cart`, `Wishlist`

**Controllers**:

- `ProductController` - Product CRUD and display
- `OrderController` - Order management
- `CartController` - Shopping cart operations
- `CheckoutController` - Order placement
- `UserController` - User account management

### MLM Module

**Location**: `app/Modules/MLM/`

**Purpose**: Multi-level marketing and referral commission system

**Key Features**:

- 3-level commission structure
- Automatic commission calculation on order delivery
- Digital wallet system
- Withdrawal management
- Referral tree visualization
- MLM analytics and reports

**Main Models**:

- `MlmCommission` - Commission records with status workflow
- `MlmCommissionLog` - Audit trail for commission activities
- `WalletTransaction` - Wallet credit/debit transactions
- `Withdrawal` - Withdrawal requests and processing

**Services**:

- `MlmCommissionService` - Commission calculation logic
  - `processCommissionsForOrder($order)` - Calculate 3-level commissions
  - Idempotent commission creation
  - Wallet integration
- `ReferralTreeService` - Genealogy tree management
  - `getReferralTree($userId)` - Get downline structure
  - `getTotalReferralCount($userId)` - Count total referrals

**Observers**:

- `OrderObserverForMLM` - Listens for order status changes
  - Triggers commission calculation when order is delivered
  - Logs MLM activities

**Commission Flow**:

1. Customer A (Level 1 referrer) refers Customer B
2. Customer B refers Customer C (Level 2)
3. Customer C refers Customer D (Level 3)
4. When Customer D places an order and it's delivered:
   - Commission created for Customer A (Level 1) - e.g., 5% of order value
   - Commission created for Customer B (Level 2) - e.g., 3% of order value
   - Commission created for Customer C (Level 3) - e.g., 2% of order value
5. Commissions go through workflow: Pending → Approved → Paid
6. Upon approval, commission amount credited to referrer's wallet

### INVENTORY Module

**Location**: `app/Modules/INVENTORY/`

**Purpose**: Stock and warehouse management

**Key Features**:

- Multi-warehouse support with room and bin tracking
- Purchase order management
- Supplier management
- Stock tracking and reports

**Main Models**:

- `Warehouse` - Physical storage locations
- `ProductPurchaseOrder` - Purchase orders from suppliers
- `ProductStock` - Stock levels by product and warehouse
- `Supplier` - Supplier information and types

**Controllers**:

- `WarehouseController` - Warehouse CRUD
- `PurchaseOrderController` - PO creation and approval
- `StockController` - Stock reports and adjustments

### ACCOUNTS Module

**Location**: `app/Modules/ACCOUNTS/`

**Purpose**: Integrated accounting and financial management

**Key Features**:

- Chart of accounts with hierarchy
- Double-entry bookkeeping
- Voucher system (Payment, Receipt, Journal, Contra)
- Expense tracking
- Financial reports

**Main Models**:

- `Account` - Chart of accounts
- `AccountType` - Account classification (Asset, Liability, Equity, Revenue, Expense)
- `AccountGroup` - Account groupings
- `Transaction` - Financial transactions
- `TransactionDetail` - Transaction line items (debit/credit)
- `Voucher` - Voucher headers

**Accounting Flow**:

1. Order placed → Creates receivable
2. Order paid → Records payment voucher
3. Purchase made → Creates payable
4. Payment to supplier → Records payment voucher
5. Expense incurred → Records expense and journal voucher

**Financial Reports**:

- Ledger Report: Account-wise transactions
- Trial Balance: Verify debits = credits
- Income Statement: Revenue - Expenses = Profit/Loss
- Balance Sheet: Assets = Liabilities + Equity

### CRM Module

**Location**: `app/Modules/CRM/`

**Purpose**: Customer relationship and support management

**Key Features**:

- Customer profile management
- Contact and follow-up tracking
- Support ticket system
- Newsletter management

**Main Models**:

- `Customer` - Customer profiles
- `Contact` - Contact form submissions
- `SupportTicket` - Support tickets
- `TicketMessage` - Ticket conversation
- `NewsletterSubscriber` - Email subscribers

**Controllers**:

- `CustomerController` - Customer management
- `SupportTicketController` - Ticket handling
- `ContactController` - Contact requests

## API Documentation

### Base URL

```
http://your-domain.com/api
```

### Authentication

The API uses Laravel Sanctum for token-based authentication.

#### Registration

```http
POST /api/user/registration
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "mobile": "+1234567890",
  "password": "secure_password",
  "password_confirmation": "secure_password",
  "referral_code": "ABC123" // Optional
}
```

**Response**:

```json
{
  "success": true,
  "message": "Registration successful. Please verify your email.",
  "token": "1|abcdef123456...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  }
}
```

#### Login

```http
POST /api/user/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "secure_password"
}
```

**Response**:

```json
{
  "success": true,
  "token": "2|def789ghi012...",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "wallet_balance": 150.0
  }
}
```

#### Authenticated Requests

Include the token in the Authorization header:

```http
Authorization: Bearer {token}
```

### Products

#### Get All Products

```http
GET /api/get/all/products?page=1&per_page=20
```

**Query Parameters**:

- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 20)

**Response**:

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro",
      "slug": "iphone-15-pro",
      "price": 999.0,
      "sale_price": 899.0,
      "image": "https://domain.com/uploads/products/iphone-15.jpg",
      "category": "Smartphones",
      "brand": "Apple",
      "rating": 4.5,
      "reviews_count": 120
    }
  ],
  "meta": {
    "current_page": 1,
    "total": 250,
    "per_page": 20
  }
}
```

#### Product Details

```http
GET /api/product/details/{id}
```

**Response**:

```json
{
  "success": true,
  "product": {
    "id": 1,
    "name": "iPhone 15 Pro",
    "description": "Latest flagship smartphone...",
    "price": 999.00,
    "sale_price": 899.00,
    "stock": 50,
    "images": [
      "https://domain.com/uploads/products/iphone-15-1.jpg",
      "https://domain.com/uploads/products/iphone-15-2.jpg"
    ],
    "variants": [
      {
        "id": 1,
        "color": "Space Black",
        "storage": "256GB",
        "price": 899.00,
        "stock": 20
      }
    ],
    "reviews": [...],
    "related_products": [...]
  }
}
```

#### Search Products

```http
POST /api/search/products
Content-Type: application/json

{
  "query": "iphone"
}
```

#### Filter Products

```http
POST /api/filter/products
Content-Type: application/json

{
  "category_id": 1,
  "min_price": 500,
  "max_price": 1000,
  "brands": [1, 2, 3],
  "sort": "price_asc" // price_asc, price_desc, newest, popular
}
```

### Cart

#### Add to Cart

```http
POST /api/add/to/cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1,
  "variant_id": 1, // Optional
  "quantity": 2
}
```

#### Get Cart Items

```http
POST /api/get/cart/items
Authorization: Bearer {token}
```

**Response**:

```json
{
  "success": true,
  "cart_items": [
    {
      "id": 1,
      "product": {
        "id": 1,
        "name": "iPhone 15 Pro",
        "image": "...",
        "price": 899.0
      },
      "quantity": 2,
      "subtotal": 1798.0
    }
  ],
  "cart_total": 1798.0,
  "cart_count": 2
}
```

#### Update Cart

```http
POST /api/update/cart
Authorization: Bearer {token}
Content-Type: application/json

{
  "cart_id": 1,
  "quantity": 3
}
```

#### Remove from Cart

```http
POST /api/remove/cart/item
Authorization: Bearer {token}
Content-Type: application/json

{
  "cart_id": 1
}
```

### Checkout

#### Apply Coupon

```http
POST /api/apply/coupon
Authorization: Bearer {token}
Content-Type: application/json

{
  "coupon_code": "SAVE20"
}
```

#### Checkout

```http
POST /api/order/cart/checkout
Authorization: Bearer {token}
Content-Type: application/json

{
  "shipping_address_id": 1,
  "billing_address_id": 1,
  "payment_method": "cod", // cod, card, bkash, nagad, etc.
  "delivery_method": "home_delivery",
  "coupon_code": "SAVE20", // Optional
  "notes": "Please deliver after 6 PM" // Optional
}
```

**Response**:

```json
{
  "success": true,
  "message": "Order placed successfully",
  "order": {
    "id": 1,
    "order_number": "ORD-2024-001",
    "total_amount": 1798.0,
    "status": "pending",
    "payment_status": "unpaid"
  }
}
```

### Orders

#### Get My Orders

```http
GET /api/get/my/orders?status=all
Authorization: Bearer {token}
```

**Query Parameters**:

- `status`: all, pending, approved, delivered, cancelled

#### Order Details

```http
GET /api/order/details/{order_slug}
Authorization: Bearer {token}
```

#### Track Order

```http
POST /api/order/progress
Authorization: Bearer {token}
Content-Type: application/json

{
  "order_id": 1
}
```

### Wishlist

#### Add to Wishlist

```http
POST /api/add/to/wishlist
Authorization: Bearer {token}
Content-Type: application/json

{
  "product_id": 1
}
```

#### Get Wishlist

```http
GET /api/get/my/wishlist
Authorization: Bearer {token}
```

#### Remove from Wishlist

```http
DELETE /api/wishlist/remove/{product_id}
Authorization: Bearer {token}
```

### User Profile

#### Get Profile

```http
GET /api/user/profile/info
Authorization: Bearer {token}
```

#### Update Profile

```http
POST /api/user/profile/update
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe",
  "mobile": "+1234567890",
  "email": "john@example.com"
}
```

#### Add Address

```http
POST /api/add/new/address
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Home",
  "address": "123 Main St",
  "city": "New York",
  "state": "NY",
  "postal_code": "10001",
  "country": "USA",
  "phone": "+1234567890",
  "is_default": true
}
```

#### Add Payment Card

```http
POST /api/add/new/card
Authorization: Bearer {token}
Content-Type: application/json

{
  "card_number": "4111111111111111",
  "cardholder_name": "John Doe",
  "expiry_month": "12",
  "expiry_year": "2025",
  "cvv": "123"
}
```

### Support

#### Submit Ticket

```http
POST /api/submit/support/ticket
Authorization: Bearer {token}
Content-Type: multipart/form-data

subject=Issue with order
message=I haven't received my order yet
priority=high
attachment=@file.jpg
```

#### Get Tickets

```http
GET /api/get/all/support/tickets
Authorization: Bearer {token}
```

### Content APIs

#### Sliders

```http
GET /api/get/all/sliders
```

#### Banners

```http
GET /api/get/all/banners?position=home
```

#### FAQs

```http
GET /api/get/all/faq
```

#### Blogs

```http
GET /api/get/all/blogs
```

#### Blog Details

```http
GET /api/blog/details/{slug}
```

### MLM APIs

#### My Referral Stats

```http
GET /api/mlm/my/stats
Authorization: Bearer {token}
```

**Response**:

```json
{
  "success": true,
  "data": {
    "referral_code": "ABC123",
    "total_referrals": 15,
    "level_1_count": 5,
    "level_2_count": 7,
    "level_3_count": 3,
    "total_earnings": 500.0,
    "pending_commissions": 150.0,
    "wallet_balance": 350.0
  }
}
```

#### Referral Tree

```http
GET /api/mlm/referral/tree
Authorization: Bearer {token}
```

#### Commission History

```http
GET /api/mlm/commission/history?status=all
Authorization: Bearer {token}
```

#### Request Withdrawal

```http
POST /api/mlm/request/withdrawal
Authorization: Bearer {token}
Content-Type: application/json

{
  "amount": 100.00,
  "payment_method": "bank_transfer",
  "account_details": "Account: 123456789"
}
```

### Error Responses

All API errors follow this format:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

**Common HTTP Status Codes**:

- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized (invalid or missing token)
- `403`: Forbidden (insufficient permissions)
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error

## Configuration

### Email Configuration

#### Gmail SMTP

Edit `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Note**: For Gmail, you need to create an [App Password](https://support.google.com/accounts/answer/185833) instead of using your regular password.

### Payment Gateway Configuration

#### SSLCommerz (Bangladesh)

1. Register at [SSLCommerz](https://www.sslcommerz.com/)
2. Get Store ID and Store Password
3. Update `.env`:

```env
SSLCZ_STORE_ID=your_store_id
SSLCZ_STORE_PASSWORD=your_store_password
SSLCZ_TESTMODE=true  # false for production
```

4. Admin panel configuration at `/admin/settings/payment-gateway`

### Social Login Configuration

#### Google OAuth

1. Create project at [Google Cloud Console](https://console.cloud.google.com/)
2. Enable Google+ API
3. Create OAuth 2.0 credentials
4. Add authorized redirect URI: `http://your-domain.com/auth/google/callback`
5. Update `.env`:

```env
GOOGLE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REDIRECT_URL=http://your-domain.com/auth/google/callback
```

#### Facebook OAuth

```env
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URL=http://your-domain.com/auth/facebook/callback
```

### Firebase Push Notifications

1. Create Firebase project at [Firebase Console](https://console.firebase.google.com/)
2. Download service account JSON
3. Place it in `storage/app/firebase/`
4. Update `.env`:

```env
FIREBASE_CREDENTIALS=storage/app/firebase/your-project-firebase.json
```

### MLM Configuration

Configure commission percentages in admin panel or database:

**Database Table**: `mlm_settings`

```sql
-- Example commission rates
Level 1: 5% of order value
Level 2: 3% of order value
Level 3: 2% of order value
```

Configure in admin panel at `/admin/mlm/settings`

### SMS Configuration

Configure SMS gateway credentials in admin panel at `/admin/settings/sms`

Supported gateways:

- TwoWay SMS
- BulkSMS BD
- API-based SMS services

## Database Schema

### Core Tables

#### users

```sql
- id (PK)
- name
- email (unique)
- mobile (unique)
- password
- user_type (1=Admin, 2=System User, 3=Customer, 4=Delivery)
- referral_code (unique)
- parent_referrer_id (FK to users)
- wallet_balance (decimal)
- is_verified
- created_at, updated_at
```

#### orders

```sql
- id (PK)
- user_id (FK)
- order_number (unique)
- total_amount
- discount_amount
- shipping_charge
- grand_total
- payment_method
- payment_status
- order_status (pending, approved, dispatch, intransit, delivered, cancelled, return)
- delivery_method
- source (web, app, pos, social)
- created_at, updated_at
```

#### products

```sql
- id (PK)
- name
- slug (unique)
- description
- price
- sale_price
- stock
- category_id (FK)
- subcategory_id (FK)
- child_category_id (FK)
- brand_id (FK)
- is_package (boolean)
- created_at, updated_at
```

#### mlm_commissions

```sql
- id (PK)
- order_id (FK)
- buyer_id (FK to users)
- referrer_id (FK to users)
- level (1, 2, or 3)
- commission_amount
- commission_percentage
- status (pending, approved, paid, rejected)
- wallet_transaction_id (FK)
- created_at, updated_at
```

#### wallet_transactions

```sql
- id (PK)
- user_id (FK)
- type (credit, debit)
- amount
- balance_before
- balance_after
- description
- reference_type (commission, order, withdrawal)
- reference_id
- created_at, updated_at
```

#### product_stocks

```sql
- id (PK)
- product_id (FK)
- warehouse_id (FK)
- quantity
- updated_at
```

#### ac_accounts (Chart of Accounts)

```sql
- id (PK)
- account_name
- account_code (unique)
- account_type_id (FK)
- account_group_id (FK)
- parent_account_id (FK to ac_accounts)
- opening_balance
- current_balance
- created_at, updated_at
```

#### ac_transactions

```sql
- id (PK)
- transaction_date
- voucher_type (payment, receipt, journal, contra)
- reference_number
- description
- total_amount
- created_by (FK to users)
- created_at, updated_at
```

### Relationships Diagram

```
users
  ├── hasMany orders (as buyer)
  ├── hasMany referrals (users, as parent)
  ├── belongsTo parent (user, as referrer)
  ├── hasMany mlm_commissions (as referrer)
  ├── hasMany wallet_transactions
  └── hasMany addresses

orders
  ├── belongsTo user
  ├── hasMany order_details
  ├── hasOne shipping_info
  ├── hasOne billing_address
  └── hasMany mlm_commissions

products
  ├── belongsTo category
  ├── belongsTo subcategory
  ├── belongsTo brand
  ├── hasMany variants
  ├── hasMany reviews
  └── hasMany stocks

mlm_commissions
  ├── belongsTo order
  ├── belongsTo buyer (user)
  ├── belongsTo referrer (user)
  └── hasOne wallet_transaction
```

## Authentication & Authorization

### User Types

```php
const ADMIN = 1;         // Full system access
const SYSTEM_USER = 2;   // Limited backend access
const CUSTOMER = 3;      // Frontend customer with MLM
const DELIVERY_BOY = 4;  // Delivery personnel
```

### Authentication Guards

#### Web Guard (Session-based)

- **Provider**: Users table
- **Used for**: Regular web application
- **Routes**: `/`, `/shop`, `/cart`, etc.

#### Admin Guard (Session-based)

- **Provider**: Users table
- **Middleware**: `auth:admin`, `CheckUserType:1,2`
- **Used for**: Admin panel
- **Routes**: `/admin/*`

#### Customer Guard (Session-based)

- **Provider**: Users table
- **Middleware**: `auth:customer`, `CheckUserType:3`
- **Used for**: Customer dashboard
- **Routes**: `/account/*`

#### Sanctum Guard (Token-based)

- **Provider**: Users table
- **Used for**: API authentication
- **Routes**: `/api/*` (protected endpoints)

### Middleware

#### CheckUserType

Validates that the authenticated user has the correct user type.

```php
// Route example
Route::middleware(['auth:admin', 'CheckUserType:1,2'])->group(function () {
    // Only Admin (1) and System User (2) can access
});
```

#### DemoMode

Prevents destructive actions in demo mode.

```php
if (config('app.demo_mode')) {
    return redirect()->back()->with('error', 'This action is disabled in demo mode');
}
```

#### TrackUserActivity

Logs user activities for audit trail.

#### CheckUserVerification

Ensures user has verified their email before accessing certain features.

### Role-Based Access Control

While the system uses user types for basic access control, you can extend it with Laravel's built-in authorization features:

```php
// Define gates in AuthServiceProvider
Gate::define('manage-orders', function ($user) {
    return in_array($user->user_type, [1, 2]);
});

// Use in controllers
if (Gate::allows('manage-orders')) {
    // ...
}
```

### API Token Management

Tokens are created on login:

```php
$token = $user->createToken('api-token')->plainTextToken;
```

Tokens can be revoked:

```php
// Revoke current token
$request->user()->currentAccessToken()->delete();

// Revoke all tokens
$request->user()->tokens()->delete();
```

## Payment Gateways

### Supported Payment Methods

1. **Cash on Delivery (COD)**
   - No configuration required
   - Payment collected on delivery

2. **SSLCommerz** (Primary gateway for Bangladesh)
   - Credit/Debit cards
   - Mobile banking (bKash, Nagad, Rocket)
   - Internet banking

3. **bKash** (Mobile wallet - Bangladesh)
4. **Nagad** (Mobile wallet - Bangladesh)
5. **Bank Transfer**
6. **PayPal** (International)
7. **Stripe** (International)

### Implementing New Payment Gateway

1. Create gateway configuration:

```php
// database/migrations/xxxx_add_new_gateway_to_payment_gateways.php
PaymentGateway::create([
    'name' => 'NewGateway',
    'code' => 'new_gateway',
    'credentials' => json_encode([
        'api_key' => '',
        'api_secret' => ''
    ]),
    'is_active' => true
]);
```

2. Create payment service:

```php
// app/Services/Payment/NewGatewayService.php
class NewGatewayService
{
    public function initiatePayment($order)
    {
        // Gateway-specific logic
    }

    public function verifyPayment($transactionId)
    {
        // Verification logic
    }
}
```

3. Add route for callback:

```php
// routes/paymentRoutes.php
Route::post('/payment/new-gateway/callback', [NewGatewayController::class, 'callback']);
```

4. Configure in admin panel at `/admin/settings/payment-gateway`

## Deployment

### Production Checklist

#### 1. Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-production-domain.com
```

#### 2. Database Setup

```bash
# Run migrations on production
php artisan migrate:modules --force

# DO NOT use --fresh on production!
```

#### 3. Optimize Application

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

#### 4. Security

```bash
# Generate new application key (ONLY on first deployment)
php artisan key:generate

# Set proper directory permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 5. Queue Worker

Set up a supervisor or systemd service to run queue workers:

```ini
# /etc/supervisor/conf.d/laravel-worker.conf
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/tp-ecommerce/artisan queue:work
autostart=true
autorestart=true
user=www-data
numprocs=3
redirect_stderr=true
stdout_logfile=/path/to/tp-ecommerce/storage/logs/worker.log
```

#### 6. Cron Jobs

Add to crontab for scheduled tasks:

```bash
* * * * * cd /path/to/tp-ecommerce && php artisan schedule:run >> /dev/null 2>&1
```

#### 7. SSL Certificate

Install SSL certificate (Let's Encrypt recommended):

```bash
sudo certbot --apache -d your-domain.com -d www.your-domain.com
```

#### 8. Web Server Configuration

**Apache Virtual Host**:

```apache
<VirtualHost *:80>
    ServerName your-domain.com
    ServerAlias www.your-domain.com
    DocumentRoot /path/to/tp-ecommerce/public

    <Directory /path/to/tp-ecommerce/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/tp-ecommerce-error.log
    CustomLog ${APACHE_LOG_DIR}/tp-ecommerce-access.log combined
</VirtualHost>
```

**Nginx Configuration**:

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /path/to/tp-ecommerce/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

#### 9. Monitoring

Set up application monitoring:

- Laravel Telescope (for debugging in staging)
- Laravel Horizon (for queue monitoring)
- Log monitoring (Loggly, Papertrail, or ELK stack)
- Application performance monitoring (New Relic, Scout APM)

### Deployment Scripts

**Simple Deployment Script** (`deploy.sh`):

```bash
#!/bin/bash

echo "Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run production

# Run migrations
php artisan migrate:modules --force

# Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
php artisan queue:restart

# Set permissions
chmod -R 775 storage bootstrap/cache

echo "Deployment complete!"
```

Make executable: `chmod +x deploy.sh`

### Docker Deployment

**Dockerfile**:

```dockerfile
FROM php:8.0-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

**docker-compose.yml**:

```yaml
version: "3"
services:
  app:
    build: .
    container_name: tp-ecommerce-app
    volumes:
      - .:/var/www
    networks:
      - tp-ecommerce

  web:
    image: nginx:alpine
    container_name: tp-ecommerce-web
    ports:
      - "80:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/conf.d:/etc/nginx/conf.d
    networks:
      - tp-ecommerce

  db:
    image: mysql:8.0
    container_name: tp-ecommerce-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: yninetwo_mlm_ecom
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - tp-ecommerce

networks:
  tp-ecommerce:
    driver: bridge

volumes:
  dbdata:
```

## Development Guidelines

### Coding Standards

- Follow **PSR-12** coding standards
- Use **Laravel best practices**
- Type-hint all method parameters and return types
- Write meaningful variable and method names
- Add PHPDoc blocks for complex methods

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes and commit
git add .
git commit -m "Add new feature: description"

# Push to remote
git push origin feature/new-feature

# Create Pull Request
# After review and approval, merge to main
```

### Database Migrations

Always create migrations for database changes:

```bash
# For core tables
php artisan make:migration create_table_name

# For module tables
# Create in: app/Modules/MODULE_NAME/Database/Migrations/
```

**Migration Best Practices**:

- Always define `up()` and `down()` methods
- Use `foreignId` and `constrained()` for foreign keys
- Add indexes on frequently queried columns
- Never modify existing migrations in production

### Testing

```bash
# Run phpunit tests
php artisan test

# Run specific test
php artisan test --filter=TestClassName
```

### Code Review Checklist

- [ ] Code follows PSR-12 standards
- [ ] No hardcoded values (use config)
- [ ] Proper validation on all inputs
- [ ] Database queries optimized (use eager loading)
- [ ] Proper error handling
- [ ] Security vulnerabilities checked (SQL injection, XSS, CSRF)
- [ ] Sensitive data not logged
- [ ] API endpoints documented
- [ ] Migrations tested (up and down)

## Troubleshooting

### Common Issues

#### 1. 500 Internal Server Error

**Solution**:

```bash
# Enable debug mode temporarily
APP_DEBUG=true in .env

# Check error logs
tail -f storage/logs/laravel.log

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Permission Denied Errors

**Solution**:

```bash
# Linux/macOS
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# SELinux (if enabled)
chcon -R -t httpd_sys_rw_content_t storage bootstrap/cache
```

#### 3. Class Not Found Errors

**Solution**:

```bash
composer dump-autoload -o
php artisan clear-compiled
```

#### 4. Migration Errors

```bash
# Check migration status
php artisan migrate:status

# Rollback last migration
php artisan migrate:rollback --step=1

# For module migrations
php artisan migrate:modules --pretend  # Preview SQL
```

#### 5. Queue Not Processing

**Solution**:

```bash
# Check failed jobs
php artisan queue:failed

# Restart queue workers
php artisan queue:restart

# Process failed jobs
php artisan queue:retry all
```

#### 6. CORS Errors (API)

**Solution**:
Edit `config/cors.php`:

```php
'allowed_origins' => ['http://localhost:3000', 'https://your-app.com'],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
```

#### 7. Payment Gateway Failing

**Checklist**:

- Verify credentials in `.env`
- Check gateway test/live mode
- Review payment gateway logs
- Verify callback URLs are accessible
- Check firewall rules

#### 8. MLM Commissions Not Generated

**Troubleshooting**:

1. Check if order status is "delivered"
2. Verify referral relationships in database:
   ```sql
   SELECT id, name, email, referral_code, parent_referrer_id FROM users WHERE id = {buyer_id};
   ```
3. Check MLM observer is registered in `AppServiceProvider`:
   ```php
   Order::observe(OrderObserverForMLM::class);
   ```
4. Check commission logs:
   ```sql
   SELECT * FROM mlm_commission_logs WHERE order_id = {order_id};
   ```

#### 9. Emails Not Sending

**Diagnosis**:

```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

**Solutions**:

- Verify SMTP credentials
- Check firewall allows outbound port 587/465
- For Gmail, use App Password
- Check `storage/logs/laravel.log` for errors

#### 10. Images Not Displaying

**Solutions**:

```bash
# Ensure storage link exists
php artisan storage:link

# Verify file permissions
chmod -R 775 public/uploads storage/app/public

# Check .env
FILESYSTEM_DRIVER=public
```

### Debug Mode

**Enable detailed errors** (development only):

```env
APP_DEBUG=true
APP_ENV=local
```

**Query Logging**:

```php
// In AppServiceProvider boot()
DB::listen(function($query) {
    logger()->info($query->sql, $query->bindings);
});
```

### Performance Issues

**Optimization Checklist**:

1. **Enable Caching**

   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimize Database Queries**
   - Use eager loading: `with(['relation1', 'relation2'])`
   - Add database indexes
   - Use `select()` to limit columns

3. **Enable OpCache** (php.ini)

   ```ini
   opcache.enable=1
   opcache.memory_consumption=256
   opcache.max_accelerated_files=10000
   ```

4. **Use Queue for Heavy Tasks**

   ```php
   dispatch(new ProcessOrder($order));
   ```

5. **Image Optimization**
   - Compress images before upload
   - Use lazy loading
   - Serve via CDN

## License

This project is proprietary software developed by YNINetwork. All rights reserved. Unauthorized copying, distribution, or modification is strictly prohibited.

For licensing inquiries, contact: [your-contact-email]

---

## Support & Contact

**Technical Support**: support@yninetwork.com
**Sales**: sales@yninetwork.com
**Documentation**: https://docs.yninetwork.com
**Bug Reports**: https://github.com/your-repo/issues

---

**Version**: 1.0.0
**Last Updated**: April 2026
**Maintained By**: YNINetwork Development Team

---

Built with ❤️ using Laravel
