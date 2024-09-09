# Back-End of the Inventory Control Application - Market

This is the repository of the back-end of the Inventory Control application, developed in PHP 8.2. This back-end is designed exclusively to handle API routes, offering simple and efficient route management, with excellent performance. The routes also have authentication for better application security. It connects to the PostgreSQL database and follows the MVC standard, maintaining a clean and organized code.

## Technologies Used

The Inventory Control Application for the market was developed using the following technologies, frameworks and libraries:

- **PHP 8.2:**
- **PostgreSQL:**

In addition to the technologies mentioned above, the MVC standard was used to maintain an organized and easy-to-maintain code.

## Functionalities

The back-end is responsible for connecting to the database and processing the information from the requests, providing the front-end with all the necessary functionalities and data processing.

### Inventory Control

Inventory Control provides a summary of the quantities of products available based on the registered purchase and sale transactions.

### Product CRUD

- **Product List:** View all registered products, including details such as name, type and quantity in stock.
- **Product Registration:** Add new products with information such as name, description, price and type.
- **Product Editing:** Update the information of an existing product.
- **Product Deletion:** Performs a soft delete (marks the deleted_at column).

### Product Types

- **Product Type List:** View a list of all registered product types.
- **Product Type Registration:** Create new product types to categorize your products.
- **Product Type Deletion:** Performs a soft delete (marks the deleted_at column).

### Purchase and Sale Transactions

- **Purchase Record:** Record the purchase of products, specifying the quantity, date and supplier.
- **Sales Record:** Record the sale of products, specifying the quantity, date and customer.
- **Transaction History:** View a complete history of all purchase and sale transactions performed.

### API Routes

The following are the API routes and their respective controllers:

#### GET Routes (With authentication)

- **GET /api/products-list:** Returns the list of products. Controller: `ProductController@listProducts`.
- **GET /api/product-get:** Returns details of a specific product. Controller: `ProductController@getProduct`.
- **GET /api/product-delete:** Marks a product as deleted (soft delete). Controller: `ProductController@deleteProduct`.
- **GET /api/product-types-list:** Returns the list of product types. Controller: `ProductController@listProductTypes`.
- **GET /api/product-type-delete:** Marks a product type as deleted (soft delete). Controller: `ProductController@deleteProductType`.
- **GET /api/transaction-list:** Returns the list of transactions. Controller: `TransactionController@listTransaction`.

#### POST Routes (With authentication)

- **POST /api/product-create:** Creates a new product. Controller: `ProductController@createProduct`.
- **POST /api/product-update:** Updates a product's information. Controller: `ProductController@updateProduct`.
- **POST /api/product-type-create:** Creates a new product type. Controller: `ProductController@createProductType`.
- **POST /api/transaction-purchase:** Records a purchase transaction. Controller: `TransactionController@purchaseTransaction`.
- **POST /api/transaction-sale:** Records a sales transaction. Controller: `TransactionController@salesTransaction`.

#### POST Routes (No Authentication)
- **POST /api/user-create:** Registers a new user. Controller: `UserController@createUser`.
- **POST /api/user-login:** Authenticates the user. Controller: `AuthController@login`.

Make sure to adjust the routes and controllers according to the actual structure of your project.

## Execution

To run the application, follow the steps below:

1. **Clone this repository:**
   ```sh
   git clone https://github.com/FilipeNSV/marked-stock-control

2. **Navigate to the project directory:**
   ```sh
   cd your-repository

3. **Run the local PHP server on the desired port (specify a port that does not conflict with the front-end):**
   ```sh
   php -S localhost:8080 -t public

4. **Configure the .env file with the database information and make a JWT_KEY:** 
Example:
   ```sh
   DB_CONNECTION='pgsql' 
   DB_HOST='localhost' 
   DB_PORT='5432' 
   DB_DATABASE='db_market' 
   DB_USERNAME='postgres' 
   DB_PASSWORD='123' 
 
   JWT_KEY=FlhTMdzn7V8KxvFUdsdass61Vyj8To55AXpDE1yDjpsPIpJcjcdsadas3h0skxARpzq