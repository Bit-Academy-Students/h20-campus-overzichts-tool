# API endpoints

Routes staan in `campus-overzichts-tool/routes/api.php` (prefix `/api`) en geven JSON terug.
Stuur altijd `Accept: application/json` mee.

## Users

| Methode | URL | Doel |
|---|---|---|
| GET | `/api/users` | alle users |
| GET | `/api/users/{id}` | één user |
| POST | `/api/users` | nieuwe user (rol is altijd `pending`) |
| PATCH | `/api/users/{id}` | user wijzigen / goedkeuren |
| DELETE | `/api/users/{id}` | user verwijderen |

Fouten: `404` bestaat niet, `422` validatie mislukt.

## Login

| Methode | URL | Doel |
|---|---|---|
| POST | `/login` | inloggen (staat in `web.php`, niet onder `/api`) |

Body: `email`, `password`, optioneel `remember`. Stuur `X-CSRF-TOKEN` mee.
Antwoord: `200` met `{ "redirect": "..." }`, of `422` bij foute gegevens.

## Nog te doen

Assignments, AssignmentClaim, Assignment_Tags, Client, ClientTag, ContactPerson, Contractor, ContractorTag, Keywords, Sources, Tags.
