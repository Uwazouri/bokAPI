# Backend för projektuppgift - Bokhörnan
#### Av Kajsa Classon, VT25. DT210G - Fördjupad frontend-utveckling, Mittuniversitetet.

En REST-webbtjänst som hanterar användarautentisering och bokrecensioner.

Webbtjänsten är skapad med hjälp av ramverket Laravel och använder middlewaren Sanctum för autentisering.

Repo för klientapplikation:

Webbtjänsten finns publicerad på:

### Routes
#### Publika routes
| Metod         | Ändpunkt                     | Beskrivning   |
| ------------- | -------------------------    | ------------- |
| POST          | /api/login                   | Loggar in en användare [^1] |
| POST          | /api/register                | Registrerar en användare [^2] |
| ------------- | -------------------------    | ------------- |
| GET           | /api/user/{id}               | Hämtar publik information om vald användare (id, namn, bio, current_read och avatar) |
| GET           | /api/user/{id}/likedbooks    | Hämtar vald användares gillade böcker |
| GET           | /api/user/{id}/reviews       | Hämtar vald användares recensioner |
| ------------- | -------------------------    | ------------- |
| GET           | /api/reviews                 | Hämtar alla recensioner för alla böcker |
| GET           | /api/reviews/latest          | Hämtar de 5 senaste recensionerna |
| GET           | /api/review/{id}             | Hämtar en recension med valt id |
| ------------- | -------------------------    | ------------- |
| GET           | /api/book/{id}/reviews       | Hämtar alla recensioner för bok med valt id |
| GET           | /api/book/{id}/likes         | Hämtar antal likes för bok med valt id |
| GET           | /api/book/mostliked          | De fem mest gillade böckerna |

[^1] Kräver att ett user-objekt skickas med. (Endast email och password)

[^2] Kräver att ett user-objekt skickas med. Endast inloggad admin kan skapa andra admins. is_admin, bio, current_read och avatar är ej obligatoriska.

Ett user-objekt skickas som JSON med följande struktur:

``` 
{
    "name": "Test Testsson",
    "email": "test@epost.se",
    "password": "lösenord",
    "is_admin": true|false,
    "bio": "Jag gillar böcker" | null,
    "current_read": "En bok" | null,
    "avatar" : image-file | null
}
```

#### Privata routes
| Metod         | Ändpunkt                       | Beskrivning   |
| ------------- | -------------------------      | ------------- |
| POST          | /api/logout                    | Loggar ut inloggad användare |
| ------------- | -------------------------      | ------------- |
| GET           | /api/user                      | Hämtar information om inloggad användare |
| POST          | /api/user/{id}?_method=PUT     | Uppdaterar en användares profil (inklusive profilbild) [^3] |
| PUT           | /api/user/{id}                 | Uppdaterar en användares profil [^4] |
| PUT           | /user/{id}/deleteavatar        | Uppdaterar en användares profilbild till null och tar bort den ur storage [^4] |
| ------------- | -------------------------      | ------------- |
| POST          | /api/likedbooks                | Lägger till en bok i inloggad användares gillade böcker [^5] |
| DELETE        | /api/likedbooks/{id}           | Raderar bok med angivet ID från inloggad användares gillade böcker|
| ------------- | -------------------------      | ------------- |
| POST          | /api/review                    | Lägger till en recension för en bok [^6] |
| PUT           | /api/review/{id}               | Uppdaterar recension med angivet ID [^7] |
| DELETE        | /api/review/{id}               | Raderar recension med angivet ID |

[^3] Kräver att ett user-objekt skickas med (form data eller json). OBS! Endast namn, bio, current_read och avatar. 

[^4] Kräver att ett user-objekt skickas med (json). OBS! Endast namn, bio och current_read. 

[^5] Kräver att id för ett bok-objekt skickas som JSON enligt nedan.

``` 
{
    "book_id": "nBHMDwAAQBAJ",
    "title": "Collecting Cats",
    "thumbnail": "url till thumbnail"
}
```

[^6] Kräver att ett recensions-objekt skickas med.

[^7] Kräver att ett recensions-objekt skickas med. OBS! Endast rating och comment.

Ett recensions-objekt skickas som JSON-data med följande struktur:

``` 
{
    "book_id": "nBHMDwAAQBAJ",
    "book_title": "Collecting Cats",
    "rating": 5,
    "comment": "Bra bok!"
}

### **Hosting the API on a Render Web Service**

#### **Part 1: Setting Up Render Account and Services**

1. **Create an Account**:
   - Sign up at [Render.com](https://render.com) if you don’t already have an account.
   - Log in and create a new Hobby Workspace. You can name it something like “Hobby Space” for organization.

2. **Create a PostgreSQL Database**:
   - In your selected workspace, create a new PostgreSQL database.
   - Name it `bookhornan`, and optionally assign it to a new project for better organization.
   - Select **EU Central** as the region and choose the free plan (note: it expires after 30 days).

#### **Part 2: Preparing Your Laravel Project**

Ensure your project is set up to work seamlessly with Docker. These files are critical:
- `conf/nginx/nginx-site.conf`
- `scripts/00-laravel-deploy.sh`
- `.dockerignore`
- `Dockerfile`

#### **Part 3: Testing the Project Locally**

1. **Set Up the `.env` File**:
   - Create or update your `.env` file with the following variables to connect to your Render database:
     ```env
     DB_CONNECTION=pgsql
     DB_HOST=<External_DB_Host>
     DB_PORT=5432
     DB_DATABASE=<Database_Name>
     DB_USERNAME=<Database_Username>
     DB_PASSWORD=<Database_Password>
     ```
   - Use the **External Database URL** (found on the Render database Info page) to populate these variables. It follows this format:
     ```
     postgresql://DB_USERNAME:DB_PASSWORD@DB_HOST/DB_DATABASE
     ```

2. **Add Remote Access Control**:
   - On the Render database Info page, add your computer's IP address to the remote access control settings.

3. **Install Dependencies**:
   - Run these commands to set up your local environment:
     ```bash
     composer install
     npm install
     php artisan key:generate
     php artisan migrate
     ```

4. **Ensure PHP Extensions**:
   - Enable `pdo_pgsql` and `pgsql` extensions in your PHP configuration (`php.ini`).

5. **Run the Laravel Development Server**:
   - Start the server locally:
     ```bash
     php artisan serve
     ```

6. **Test the API**:
   - Use tools like Postman or Thunder Client to verify your API functionality.

#### **Part 4: Deploying to Render Web Service**

1. **Create a New Web Service**:
   - On Render, create a new Web Service and connect it to your GitHub repository (or use a public Git repo).
   - Select your Laravel API project and name the service, e.g., `bookhornanAPI`.
   - Assign it to the same project as your database.
   - Choose **Docker** as the runtime environment and select **EU Central** region with the free plan.

2. **Configure Environment Variables**:
   - Add these variables to the Web Service settings:
     ```env
     APP_KEY=<Your_App_Key>
     DB_CONNECTION=pgsql
     DB_HOST=<Internal_DB_Host>
     DB_PORT=5432
     DB_DATABASE=<Database_Name>
     DB_USERNAME=<Database_Username>
     DB_PASSWORD=<Database_Password>
     ```
   - Use the **Internal Database URL** for database-related variables.
   - Generate the `APP_KEY` locally with:
     ```bash
     php artisan key:generate --show
     ```

3. **Automatic Deployment**:
   - Once the Web Service is created, Render will automatically pull from the `main` branch and deploy your app. Future pushes to `main` will trigger redeployments.

---

Pray to the AI gods everything still works as when this was written.