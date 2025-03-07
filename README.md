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
| GET           | /api/review/{id}             | Hämtar en recension med valt id |
| ------------- | -------------------------    | ------------- |
| GET           | /api/book/{id}/reviews       | Hämtar alla recensioner för bok med valt id |

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
    "book_id": "nBHMDwAAQBAJ"
}
```

[^6] Kräver att ett recensions-objekt skickas med.

[^7] Kräver att ett recensions-objekt skickas med. OBS! Endast rating och comment.

Ett recensions-objekt skickas som JSON-data med följande struktur:

``` 
{
    "book_id": "nBHMDwAAQBAJ",
    "rating": 5,
    "comment": "Bra bok!"
}


| ------------- | -------------------------      | ------------- |
| GET           | /api/products/{id}             | Hämtar produkt med angivet ID |
| POST          | /api/products                  | Skapar en ny produkt [^4] |