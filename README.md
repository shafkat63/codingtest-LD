

<!-- POSTMAN API CHECK -->


#Register User

#Endpoint
BASE_URL: http://127.0.0.1:8000


POST /register

{
    "name": "John Doe 3",
    "email": "john3@example.com",
    "password": "secret1233",
    "password_confirmation": "secret1233"
}

Response 
SUCCESS
{
    "user": {
        "name": "John Doe 3",
        "email": "john3@example.com",
        "updated_at": "2025-12-31T12:19:02.000000Z",
        "created_at": "2025-12-31T12:19:02.000000Z",
        "id": 25
    },
    "token": "6|MbDV94NxCyvTPmaPvNNazYR3UcO0jfk1XvuyXz3Sc0cdd68c"
}

FAIL

{
    "success": false,
    "error_code": 3001,
    "message": "Registration failed: This email is already registered."
}



 Login

Endpoint

POST /login


{
    "email": "john3@example.com",
    "password": "secret1233"
}

Success Response
{
    "success": true,
    "message": "Logged in successfully. .",
    "user": {
        "id": 25,
        "name": "John Doe 3",
        "email": "john3@example.com",
        "email_verified_at": null,
        "created_at": "2025-12-31T12:19:02.000000Z",
        "updated_at": "2025-12-31T12:19:02.000000Z"
    },
    "token": "7|FECqY18xllHnyR8IiccF3OmjpJOqU2A7zkW5qU0y64458741"
}

Error Response

{
    "success": false,
    "message": "Invalid credentials"
}



Shorten URL (Authenticated)

Endpoint

POST /shorten

Headers

Authorization: Bearer YOUR_API_TOKEN
Accept: application/json

{
    "original_url": "https://www.formula1.com/en/results/2025/drivers/MAXVER01/max-verstappen"
}



Success Response

{
    "success": true,
    "short_code": "Ab3Xz9"
}


Duplicate URL Response

{
    "success": false,
    "error_code": 1001,
    "message": "This URL has already been shortened.",
    "short_code": "76pMaz"
}

Validation Error

{
    "success": false,
    "error_code": 1003,
    "message": "Invalid, expired, or missing authentication token."
}