# Oauth2-sample

1. Fitso_app 

- Client side implementation for generating access token for user authentication using O Auth 2 Authorisation code grant type.
- Added components 
--- routes
--- middleware
--- views
--- controller


2. Fitso_auth

- Authorization Server for generating access token for user authentication using O Auth 2 Authorisation code grant type. 
- Added components 
--- routes
--- views
--- controller


===============
Both The application are made using Laravel Framework.

Run (1) application on port 8000 like :- "php artisan serve"

Run (2) application on port 8001 like :- "php artisan serve --port=8001"

==============

Assumptions

1. This project uses HTTP connections
2. Client is already Registered with authorization server
3. Access token contains issuer and date of expiry information.
4. Authentication is assumed to be true
5. Verification is assumed to be True
6. Access token is generated using Openssl rsa private key
7. No Database configuration.

Objective of task was to develop architectural implementation of Oauth2 Authoriation code grant type.
