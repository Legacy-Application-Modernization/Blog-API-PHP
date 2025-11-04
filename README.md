# Blog API

A RESTful API for blog management built with PHP.

## Features

- RESTful API following PSR-12 coding standards
- Create, read, update, delete blog posts
- Filter posts by search terms
- JSON responses with proper HTTP status codes
- Layered architecture (MVC pattern)
- PDO database connection with prepared statements
- Composer autoloading (PSR-4)
- Proper error handling

## Project Structure

```
blog-api-project/
├── config/
│   └── database.php           # Database configuration
├── public/
│   ├── .htaccess              # URL rewriting rules
│   └── index.php              # Application entry point
├── src/
│   ├── Controllers/
│   │   └── PostController.php # Post controller
│   ├── Database/
│   │   └── DatabaseConnection.php # Database connection
│   ├── Exceptions/
│   │   └── ApiException.php   # API exception handler
│   ├── Models/
│   │   └── Post.php          # Post model
│   ├── Repositories/
│   │   └── PostRepository.php # Post repository
│   └── Services/
│       ├── Request.php       # Request handler
│       ├── Response.php      # Response handler
│       └── Router.php        # Router
├── composer.json             # Composer configuration
└── database.sql              # Database schema
```

## Installation

1. Clone the repository
2. Run `composer install`
3. Create a MySQL database and import `database.sql`
4. Configure database credentials in `config/database.php`
5. Set up a virtual host pointing to the `public` directory (run cd public/ php -S localhost:8000)

## API Endpoints

### Get all posts

```
GET /api/posts
```

### Search posts

```
GET /api/posts?search=keyword
```

### Get a specific post

```
GET /api/posts/{id}
```

### Create a post

```
POST /api/posts
Content-Type: application/json

{
  "title": "Post Title",
  "content": "Post content..."
}
```

### Update a post

```
PUT /api/posts/{id}
Content-Type: application/json

{
  "title": "Updated Title",
  "content": "Updated content..."
}
```

### Delete a post

```
DELETE /api/posts/{id}
```

## Response Format

### Success Response

```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Post Title",
    "content": "Post content...",
    "created_at": "2025-11-04 12:00:00",
    "updated_at": "2025-11-04 12:00:00"
  }
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field": "Error description"
  }
}
```

## HTTP Status Codes

- `200 OK`: Successful request
- `201 Created`: Resource created
- `204 No Content`: Resource deleted
- `400 Bad Request`: Invalid request
- `404 Not Found`: Resource not found
- `422 Unprocessable Entity`: Validation error
- `500 Internal Server Error`: Server error

## Extending the Project

This project can be extended with:

1. Authentication (JWT, OAuth)
2. Pagination
3. Rate limiting
4. Caching
5. Input validation middleware
6. Database migrations
7. Unit and integration tests

## License

MIT
