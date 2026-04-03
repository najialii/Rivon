# Rivon System - Model Relationships Overview

## 📊 System Architecture
This document outlines all relationships between models in the Rivon ERP system, covering inventory management, financial accounting, sales, and customer management.

---

## 🏦 **Financial Core**

### **Account** (Chart of Accounts)
- **Properties**: `name_ar`, `name_en`, `account_type`, `description_ar`, `description_en`, `code`, `currency`, `parent_id`
- **Relationships**:
  - `parent()` → `Account` (self-referential, for hierarchical account structure)
  - `entries()` → `Jentry` (hasMany) - All journal entries for this account
  - **Balance Calculation**: Automatic based on account type (asset/expense vs liability/equity/revenue)

### **Jentry** (Journal Entries) - Double-Entry Accounting
- **Properties**: `account_id`, `reference_type`, `reference_id`, `credit`, `debit`, `description_ar`, `description_en`, `currency`
- **Relationships**:
  - `account()` → `Account` (belongsTo)
  - `reference()` → `MorphTo` - Polymorphic relationship to any model (Invoice, Salary, Cost, etc.)
- **Purpose**: Core of double-entry bookkeeping system

---

## 🛒 **Sales & Invoicing**

### **Invoice** (Sales Invoices)
- **Properties**: `invoice_number`, `customer_id`, `total_amount`, `currency`, `status`, `issue_date`, `due_date`, `notes`
- **Relationships**:
  - `customer()` → `User` (belongsTo) - Customer who made the purchase
  - `items()` → `InvoiceItem` (hasMany) - Line items in the invoice
- **Business Logic**: Auto-creates journal entries when marked as 'paid'

### **InvoiceItem** (Invoice Line Items)
- **Properties**: `invoice_id`, `description`, `quantity`, `unit_price`, `subtotal`
- **Relationships**:
  - `invoice()` → `Invoice` (belongsTo)
- **Auto-calculation**: Subtotal = quantity × unit_price

### **Order** (Customer Orders)
- **Properties**: `product_id`, `quantity`, `total_price`, `order_date`, `customer_id`
- **Relationships**:
  - `product()` → `Product` (belongsTo)
  - `customer()` → `Customer` (belongsTo)

---

## 📦 **Inventory Management**

### **Product** (Central Entity)
- **Properties**: `sku`, `name_ar`, `name_en`, `brand_id`, `category_id`, `description_ar`, `description_en`, `img_path`, `munit`, `status`
- **Relationships**:
  - `category()` → `Category` (belongsTo)
  - `brand()` → `Brand` (belongsTo)
  - `supplies()` → `Supply` (hasMany) - All supply records for this product
  - `price()` → `Price` (hasOne) - Current pricing information
  - **Activity Logging**: Tracks changes to name, SKU, and status

### **Category** (Product Categories)
- **Properties**: `name_ar`, `name_en`, `description_ar`, `description_en`, `slug`
- **Relationships**:
  - `products()` → `Product` (hasMany) - All products in this category

### **Brand** (Product Brands)
- **Properties**: `name_ar`, `name_en`, `slug`, `logo_path`, `is_active`
- **Relationships**: *(None defined but implied)*
  - Should have `products()` → `Product` (hasMany)

### **Price** (Product Pricing)
- **Properties**: `product_id`, `price`, `currency`, `wholesale_price`, `retail_price`, `wholesale_min_price`
- **Relationships**:
  - `product()` → `Product` (belongsTo)

### **Inventory** (Stock Management)
- **Properties**: `product_id`, `total_qty`, `wholesale_recived_qty`, `retail_recived_qty`, `location`
- **Relationships**:
  - `product()` → `Product` (belongsTo)

---

## 🚚 **Supply Chain**

### **Supply** (Supplier Deliveries)
- **Properties**: `product_id`, `origin_type`, `cost_id`, `recived_qty`, `expiry_date`, `recived_date`
- **Relationships**:
  - `product()` → `Product` (belongsTo) - Product being supplied
  - `cost()` → `Cost` (hasMany) - Associated costs

### **Supplier** (Vendors)
- **Properties**: `supplier_name_en`, `supplier_name_ar`, `country`, `phone_num`, `email`, `address_en`, `address_ar`
- **Relationships**:
  - `supplies()` → `Supply` (hasMany) - All deliveries from this supplier

### **Cost** (Supply Chain Costs)
- **Properties**: `name_ar`, `name_en`, `description_ar`, `description_en`, `cost_price`, `currency`, `supply_id`, `cost_type`
- **Relationships**:
  - `supply()` → `Supply` (belongsTo)
- **Business Logic**: Auto-creates journal entries for expense tracking

---

## 👥 **Customer Management**

### **Customer** (Client Information)
- **Properties**: `name_ar`, `name_en`, `phone`, `email`, `c_type`, `address_ar`, `address_en`
- **Relationships**: *(None defined but implied)*
  - Should have `invoices()` → `Invoice` (hasMany)
  - Should have `orders()` → `Order` (hasMany)
  - Should have `loyaltyPoints()` → `Loyalty_Points` (hasMany)

### **Loyalty_Points** (Customer Rewards)
- **Properties**: `customer_id`, `points`, `earn_rate`, `redeem_rate`, `expiry_date`
- **Relationships**:
  - `customer()` → `Customer` (belongsTo)

---

## 💼 **Human Resources**

### **Salary** (Employee Payments)
- **Properties**: `employee_id`, `amount`, `currency`, `code`
- **Relationships**:
  - `employee()` → `User` (belongsTo) - Employee receiving salary
- **Business Logic**: Auto-creates journal entries for expense tracking

### **User** (System Users)
- **Properties**: `name`, `email`, `password` (standard Laravel User)
- **Relationships**:
  - `invoices()` → `Invoice` (hasMany) - When user acts as customer
- **Roles**: Uses Spatie Permissions for role-based access

---

## 🔗 **Key System Integrations**

### **Financial Integration**
- **Invoices** → Auto-create journal entries when paid
- **Salaries** → Auto-create expense journal entries
- **Costs** → Auto-create expense journal entries
- **All** → Reference polymorphic Jentry for audit trail

### **Inventory Flow**
```
Supplier → Supply → Product → Inventory → Order → Invoice → Payment
    ↓         ↓        ↓         ↓        ↓        ↓
  Cost → Jentry  Price → Stock  Sales → Revenue → Journal Entry
```

### **Data Flow**
1. **Supply Chain**: Supplier → Supply → Product (with costs)
2. **Inventory**: Product → Inventory (stock levels)
3. **Sales**: Customer → Order → Invoice → Payment
4. **Accounting**: All transactions → Jentry → Account → Financial Reports

---

## ⚠️ **Missing Relationships & Recommendations**

### **Critical Missing Relationships**:
1. **Brand**: Should have `products()` relationship
2. **Customer**: Should have `invoices()`, `orders()`, `loyaltyPoints()` relationships
3. **Invoice**: Missing `InvoiceItem` model (referenced but not created)
4. **User**: Should have proper customer relationship mapping

### **Recommended Improvements**:
1. **Add soft deletes** for audit trail
2. **Add timestamps** to all models for tracking
3. **Implement proper foreign key constraints** in database
4. **Add validation rules** for data integrity
5. **Create pivot tables** for many-to-many relationships where needed

---

## 📈 **Business Intelligence Potential**

With these relationships, you can generate:
- **Financial Reports**: P&L, Balance Sheet, Cash Flow
- **Inventory Reports**: Stock levels, turnover, valuation
- **Sales Analytics**: Top products, customer behavior, revenue trends
- **Supply Chain Metrics**: Supplier performance, cost analysis
- **Customer Insights**: Purchase history, loyalty program effectiveness

---

*Last Updated: April 3, 2026*
*System Version: Laravel 13 + Filament v5*



// supply supplier 

//cost supply should havebmultiable souurce

//complete the finances 

// complete loyalitypoints 