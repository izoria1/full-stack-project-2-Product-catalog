# Product Catalog

The Product Catalog is a full-stack web application designed to manage a dynamic inventory of products. 
It allows for the addition, listing, and deletion of products, categorized into DVDs, Books, and Furniture. 
This document outlines the project structure, setup instructions, and usage.

## Project Structure

The project is organized into several key directories and files:

- `actions/delete_product.php`: Backend script for deleting products.
- `actions/product_action.php`: Backend script for adding products.
- `classes/`: Includes PHP class definitions for products, database operations, and product factory pattern implementation.
- `public/`: Holds the front-end components.
  - `css/`: CSS files for styling.
  - `images/`: Image assets used in the UI.
  - `js/`: JavaScript files for dynamic frontend behaviors.
  - `add-product.php`: The form for adding new products.
  - `index.php`: The main product listing page.

### Key Components

- `ProductFactory`: Utilizes the Factory Pattern to create product instances.
- `Database`: Implements the Singleton Pattern for database connections.
- `Product`, `DVDProduct`, `BookProduct`, `FurnitureProduct`: Represent the product hierarchy.

## Setup Instructions

To set up the Product Catalog on your local development environment, follow these steps:

1. Clone the repository to your local machine.
2. Set up a MySQL database and import the provided SQL schema (not included in the description but assumed to be part of the project setup).
3. Configure your web server (e.g., Apache or Nginx) to serve the `public/` directory as the root of a virtual host.
4. Adjust the database connection settings in `classes/Database.php` to match your local database credentials.
5. Visit the configured virtual host in your web browser to interact with the application.

## Usage

- **Adding Products**: Navigate to `public/add-product.php` and fill out the form to add a new product. Specify the product type (DVD, Book, Furniture) to see type-specific fields.
- **Viewing Products**: The `public/index.php` page lists all added products with options to select and delete multiple products simultaneously.
- **Deleting Products**: Select one or more products on the main page and use the "Delete" button to remove them from the catalog.

Thank you for exploring the Product Catalog project!
