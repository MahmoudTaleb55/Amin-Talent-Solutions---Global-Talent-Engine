# Freelance Management System API Documentation

## Overview
This API provides a comprehensive role-based freelance management system with three main user roles: Company, Freelancer, and Admin. The system manages job requests, assignments, deliverables, and status updates with secure authentication and authorization.

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
The API uses Laravel Sanctum for token-based authentication.

### Login
**Endpoint:** `POST /auth/login`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password",
  "device_name": "My Device"
}
```

**Response:**
```json
{
  "token": "1|abc123...",
  "user": {
    "id": 1,
    "username": "johndoe",
    "email": "user@example.com",
    "role": "company"
  }
}
```

### Logout
**Endpoint:** `POST /auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
  "message": "Logged out"
}
```

## API Resources

### User Roles
- **admin**: Full system access, user management, analytics
- **company**: Job request management, freelancer hiring, progress tracking
- **freelancer**: Assignment management, deliverable submission, status updates

---

## Endpoints by Role

### 🔐 Authentication Required
All endpoints below require authentication via Bearer token.

---

## 👨‍💼 Admin Endpoints

### Get All Admins
**Endpoint:** `GET /admins`

**Role:** admin

**Response:**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "admin_level": "super",
    "permissions": "full_access",
    "user": {
      "username": "admin",
      "email": "admin@example.com",
      "role": "admin"
    }
  }
]
```

### Get Admin by ID
**Endpoint:** `GET /admins/{id}`

**Role:** admin

### Create Admin
**Endpoint:** `POST /admins`

**Role:** admin

**Request Body:**
```json
{
  "user_id": 1,
  "admin_level": "standard",
  "permissions": "user_management"
}
```

### Update Admin
**Endpoint:** `PUT /admins/{id}`

**Role:** admin

### Delete Admin
**Endpoint:** `DELETE /admins/{id}`

**Role:** admin

---

## 🏢 Company Endpoints

### Get All Companies
**Endpoint:** `GET /companies`

**Role:** company (for own data) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "user_id": 1,
    "company_name": "Tech Solutions Inc",
    "contact_person": "John Doe",
    "phone": "+1234567890",
    "address": "123 Business St",
    "user": {
      "username": "company_user",
      "email": "company@example.com",
      "role": "company"
    }
  }
]
```

### Get Company by ID
**Endpoint:** `GET /companies/{id}`

**Role:** company (own data) or public (read-only)

### Create Company Profile
**Endpoint:** `POST /companies`

**Role:** company

**Request Body:**
```json
{
  "user_id": 1,
  "company_name": "Tech Solutions Inc",
  "contact_person": "John Doe",
  "phone": "+1234567890",
  "address": "123 Business St"
}
```

### Update Company Profile
**Endpoint:** `PUT /companies/{id}`

**Role:** company

### Delete Company Profile
**Endpoint:** `DELETE /companies/{id}`

**Role:** company

---

## 📋 Job Request Endpoints

### Get All Job Requests
**Endpoint:** `GET /job-requests`

**Role:** company (own requests) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "company_id": 1,
    "description": "Develop a responsive website",
    "deadline": "2024-12-31",
    "status": "open",
    "company": {
      "company_name": "Tech Solutions Inc"
    }
  }
]
```

### Get Job Request by ID
**Endpoint:** `GET /job-requests/{id}`

**Role:** company (own requests) or public (read-only)

### Create Job Request
**Endpoint:** `POST /job-requests`

**Role:** company

**Request Body:**
```json
{
  "company_id": 1,
  "description": "Develop a responsive website with modern UI",
  "deadline": "2024-12-31",
  "status": "open"
}
```

### Update Job Request
**Endpoint:** `PUT /job-requests/{id}`

**Role:** company

### Delete Job Request
**Endpoint:** `DELETE /job-requests/{id}`

**Role:** company

---

## 🔗 Assignment Endpoints

### Get All Assignments
**Endpoint:** `GET /assignments`

**Role:** company (own assignments) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "job_request_id": 1,
    "freelancer_id": 2,
    "company_id": 1,
    "job_request": {
      "description": "Develop a responsive website"
    },
    "freelancer": {
      "user": {
        "username": "freelancer_jane"
      }
    }
  }
]
```

### Get Assignment by ID
**Endpoint:** `GET /assignments/{id}`

**Role:** company (own assignments) or public (read-only)

### Create Assignment
**Endpoint:** `POST /assignments`

**Role:** company

**Request Body:**
```json
{
  "job_request_id": 1,
  "freelancer_id": 2,
  "company_id": 1
}
```

### Update Assignment
**Endpoint:** `PUT /assignments/{id}`

**Role:** company

### Delete Assignment
**Endpoint:** `DELETE /assignments/{id}`

**Role:** company

---

## 👨‍💻 Freelancer Endpoints

### Get All Freelancers
**Endpoint:** `GET /freelancers`

**Role:** freelancer (own data) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "user_id": 2,
    "skills": "PHP, Laravel, JavaScript",
    "hourly_rate": 50.00,
    "experience": "5 years",
    "portfolio_url": "https://portfolio.example.com",
    "user": {
      "username": "freelancer_jane",
      "email": "jane@example.com",
      "role": "freelancer"
    }
  }
]
```

### Get Freelancer by ID
**Endpoint:** `GET /freelancers/{id}`

**Role:** freelancer (own data) or public (read-only)

### Create Freelancer Profile
**Endpoint:** `POST /freelancers`

**Role:** freelancer

**Request Body:**
```json
{
  "user_id": 2,
  "skills": "PHP, Laravel, JavaScript",
  "hourly_rate": 50.00,
  "experience": "5 years",
  "portfolio_url": "https://portfolio.example.com"
}
```

### Update Freelancer Profile
**Endpoint:** `PUT /freelancers/{id}`

**Role:** freelancer

### Delete Freelancer Profile
**Endpoint:** `DELETE /freelancers/{id}`

**Role:** freelancer

---

## 📦 Deliverable Endpoints

### Get All Deliverables
**Endpoint:** `GET /deliverables`

**Role:** freelancer (own deliverables) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "assignment_id": 1,
    "content": "Completed website development",
    "submitted_on": "2024-01-15",
    "status": "pending",
    "file_path": "/deliverables/website_v1.zip",
    "assignment": {
      "job_request": {
        "description": "Develop a responsive website"
      }
    }
  }
]
```

### Get Deliverable by ID
**Endpoint:** `GET /deliverables/{id}`

**Role:** freelancer (own deliverables) or public (read-only)

### Create Deliverable
**Endpoint:** `POST /deliverables`

**Role:** freelancer

**Request Body:**
```json
{
  "assignment_id": 1,
  "content": "Completed website development with all requirements",
  "submitted_on": "2024-01-15",
  "status": "pending",
  "file_path": "/deliverables/website_v1.zip"
}
```

### Update Deliverable
**Endpoint:** `PUT /deliverables/{id}`

**Role:** freelancer

### Delete Deliverable
**Endpoint:** `DELETE /deliverables/{id}`

**Role:** freelancer

### Download Deliverable File
**Endpoint:** `GET /deliverables/{id}/download`

**Role:** freelancer

**Response:** File download

---

## 📊 Status Update Endpoints

### Get All Status Updates
**Endpoint:** `GET /status-updates`

**Role:** freelancer (own updates) or public (read-only)

**Response:**
```json
[
  {
    "id": 1,
    "assignment_id": 1,
    "status": "In Progress",
    "updated_on": "2024-01-10",
    "notes": "Working on the homepage design",
    "assignment": {
      "job_request": {
        "description": "Develop a responsive website"
      }
    }
  }
]
```

### Get Status Update by ID
**Endpoint:** `GET /status-updates/{id}`

**Role:** freelancer (own updates) or public (read-only)

### Create Status Update
**Endpoint:** `POST /status-updates`

**Role:** freelancer

**Request Body:**
```json
{
  "assignment_id": 1,
  "status": "In Progress",
  "updated_on": "2024-01-10",
  "notes": "Working on the homepage design"
}
```

### Update Status Update
**Endpoint:** `PUT /status-updates/{id}`

**Role:** freelancer

### Delete Status Update
**Endpoint:** `DELETE /status-updates/{id}`

**Role:** freelancer

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "Forbidden"
}
```

### 404 Not Found
```json
{
  "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Internal server error"
}
```

---

## Rate Limiting
- API requests are rate limited to prevent abuse
- Standard limit: 60 requests per minute per user
- Authenticated users have higher limits

## File Upload
- Deliverables support file uploads
- Supported formats: PDF, DOC, DOCX, ZIP, JPG, PNG
- Maximum file size: 10MB
- Files are stored securely with restricted access

## Data Validation
All endpoints include comprehensive validation:
- Required fields are enforced
- Data types are validated
- Foreign key relationships are verified
- File uploads are validated for type and size

## Security Features
- Token-based authentication (Laravel Sanctum)
- Role-based access control
- CSRF protection
- SQL injection prevention
- XSS protection
- Secure file storage

---

## Testing with cURL

### Login Example
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password",
    "device_name": "Postman"
  }'
```

### Authenticated Request Example
```bash
curl -X GET http://localhost:8000/api/v1/companies \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## Database Schema

### Users Table
- id (primary key)
- username (string)
- email (string, unique)
- password (hashed)
- role (enum: admin, company, freelancer)
- timestamps

### Companies Table
- id (primary key)
- user_id (foreign key)
- company_name (string)
- contact_person (string)
- phone (string)
- address (text)
- timestamps

### Freelancers Table
- id (primary key)
- user_id (foreign key)
- skills (text)
- hourly_rate (decimal)
- experience (string)
- portfolio_url (string)
- timestamps

### Job Requests Table
- id (primary key)
- company_id (foreign key)
- description (text)
- deadline (date)
- status (enum: open, assigned, completed, cancelled)
- timestamps

### Assignments Table
- id (primary key)
- job_request_id (foreign key)
- freelancer_id (foreign key)
- company_id (foreign key)
- timestamps

### Deliverables Table
- id (primary key)
- assignment_id (foreign key)
- content (text)
- submitted_on (date)
- status (enum: pending, approved, rejected)
- file_path (string, nullable)
- timestamps

### Status Updates Table
- id (primary key)
- assignment_id (foreign key)
- status (string)
- updated_on (date)
- notes (text, nullable)
- timestamps

### Admins Table
- id (primary key)
- user_id (foreign key)
- admin_level (string)
- permissions (text)
- timestamps

---

## Getting Started

1. **Setup Database:** Run migrations to create tables
2. **Create Users:** Pre-create users in the database with appropriate roles
3. **Authentication:** Use login endpoint to get access token
4. **API Access:** Include Bearer token in Authorization header
5. **Role-based Access:** Ensure users only access endpoints appropriate to their role

This API provides a complete backend solution for a role-based freelance management system with comprehensive security, validation, and error handling.
