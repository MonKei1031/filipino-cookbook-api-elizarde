# Filipino Cookbook API

## API Description

The Filipino Cookbook API is a RESTful web service that provides structured information about traditional Filipino dishes, including their categories, origins, and ingredients. It is built to support developers and students who want to explore or consume Filipino culinary data programmatically.

- **Purpose:** To provide organized, queryable access to a database of Filipino foods, their preparation instructions, categories, origins, and ingredients.
- **Type of information provided:** Food names, categories, regional origins, cooking instructions, and ingredient lists.
- **Intended users:** Students, developers, and client applications that need Filipino food data for learning or integration purposes.
- **Main functions:** Retrieving all foods, retrieving a specific food by ID, searching foods by name, retrieving categories, retrieving ingredients, and adding new foods.
- **Technologies used:** PHP, Slim Framework, MySQL, Composer, JSON.

---

## Features

- Retrieve all Filipino foods with their category, origin, and ingredients
- Retrieve a single food item by its ID
- Search for foods by name (partial match)
- Retrieve all food categories
- Retrieve all ingredients
- Add a new food item, including linked ingredients
- Authenticate all `/api` requests using a Bearer token
- Return all responses in JSON format
- Handle database errors gracefully with appropriate HTTP status codes

---

## Technologies Used

- PHP
- Slim Framework 4
- MySQL
- Composer
- JSON
- Apache (XAMPP)
- Thunder Client / Postman
- Git
- GitHub

---

## Installation Instructions

```bash
git clone https://github.com/MonKei1031/filipino-cookbook-api-elizarde.git
cd filipino-cookbook-api-elizarde
composer install
```

1. Copy `config.example.php` to a real configuration file (or update the database credentials directly in `getDBConnection()` if your version does not use a config file).
2. Create a MySQL database named `filipino_cookbook_api`.
3. Import the provided `.sql` file into that database using phpMyAdmin or the MySQL CLI.
4. Update the `$host`, `$user`, and `$pass` variables in `getDBConnection()` to match your local MySQL setup.
5. Start Apache and MySQL (e.g., via XAMPP).
6. Place the project folder inside your server's `htdocs` directory.
7. Run the API by navigating to the base URL in your browser or API client.
8. Test the endpoints using Thunder Client or Postman.

---

## Database Setup

- **Database name:** `filipino_cookbook_api`
- **SQL file:** `filipino_cookbook_api.sql`

**Tables:**
- `foods`
- `categories`
- `origins`
- `ingredients`
- `food_ingredients` (junction table linking foods and ingredients)

**Table relationships:**
