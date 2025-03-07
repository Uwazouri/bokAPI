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
| POST          | /api/user/{id}?_method=PUT     | Uppdaterar en användares profil (inklusive profilbild) [^3] |
| PUT           | /api/user/{id}                 | Uppdaterar en användares profil [^4] |
| ------------- | -------------------------      | ------------- |

[^2] Kräver att ett user-objekt skickas med. OBS! Endast namn, bio, current_read och avatar. 

[^3] Kräver att ett user-objekt skickas med. OBS! Endast namn, bio och current_read. 





| ------------- | -------------------------      | ------------- |
| GET           | /api/products/{id}             | Hämtar produkt med angivet ID |
| POST          | /api/products                  | Skapar en ny produkt [^4] |
| DELETE        | /api/products/{id}             | Raderar en produkt med angivet ID |