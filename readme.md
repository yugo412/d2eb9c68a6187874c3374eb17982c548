## Installation

- Clone this repository to your local machine.
- Go to cloned directory and run the application using Docker Compose by running command below:
```bash
docker-compose up --build -d
```

Last step before accessing the app is running migration to create some required tables. Please execute command below manually after build completed.

```bash
docker exec -it php-container vendor/bin/phinx migrate -e development
```

If there is no error, you can open URL [https://localhost:8080/ping](https://localhost:8080/ping) to make sure build is successful and the app running as expected.

Other than main app, you can open [http://localhost:8081](http://localhost:8081) to access mail client based on [Mailpit](https://mailpit.axllent.org/) service.

***

Reserved ports:
- 8080 (web app)
- 8081 (mail client)
- 1015 (SMTP port)

Please make sure these port is not used by host machine to prevents conflict when building the app.

## Application Lifecycle
1. Bootstrap Application:
    - Register necessary services (e.g., database connection, cache, message queue)
    - Set up the application environment (e.g., configuration, environment variables)
    - Define and configure the logging system

2. Register Route:
    - Define a route, typically with a URL pattern and an associated controller/handler function.
    - Register the route with the application's routing system.

3. Find Matching Route:
    - When a client makes a request, the application's routing system will examine the incoming request (e.g., HTTP method, URL) and attempt to find a registered route that matches.
    - The routing system will compare the incoming request against the registered route patterns to find the best match.

4. Run Middleware (if exists):
    - If a matching route is found, the application will execute any middleware functions associated with the route.
    - Middleware functions are typically used for tasks such as authentication, authorization, logging, parsing request data, etc.
    - The middleware functions are executed in the order they were registered with the application.

5. Return the Response:
    - After any middleware functions have been executed, the application will call the controller/handler function associated with the matched route.
    - The controller/handler function will process the request, perform any necessary business logic, and generate a response.
    - The generated response (e.g., HTML, JSON, redirect) is then returned to the client.

## Testing the Application

### Authentication and Token Generation
There are 3 endpoints provided to test the app's functionality, especially for the mail service.

The first one is for authentication and creating a token. This feature simulates user registration (and login) and returns a newly created token. The token will be used to access the mail endpoints later.

Run the following command to generate a new token:
```bash
curl --location --request POST 'http://localhost:8080/auth' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json'
```

### Sending Emails
After the token is successfully generated, it can be used for authentication to access the send mail endpoint.

```bash
curl --location 'http://localhost:8080/mail/send' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {token}' \
--data-raw '{
    "address": ["yugo@vivaldi.net", "mail@yugo.me", "dedy.yugo.purwanto@gmail.com"],
    "subject": "Hello World!",
    "body": "Hello ..."
}'
```

To ensure the mail was sent successfully, you can check it from the mail client at the URL [http://localhost:8081](http://localhost:8081). Alternatively, you can check the endpoint below to confirm that the sent emails are stored in the PostgreSQL database.

```dockerfile
curl --location 'http://localhost:8080/mail' \
--header 'Accept: application/json' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {token}'
```