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

## Assignments

| Methode | URL | Doel |
|---|---|---|
| GET | `/api/assignments` | lijst (gepagineerd, standaard 25 per pagina) |
| GET | `/api/assignments/{id}` | één assignment met client, contactpersoon, tags en claim |
| POST | `/api/assignments` | nieuwe assignment |
| PATCH | `/api/assignments/{id}` | assignment wijzigen (status, priority, tags, ...) |
| DELETE | `/api/assignments/{id}` | assignment verwijderen |

Query-parameters voor `GET /api/assignments`: `status`, `priority`, `client_id`, `tag` (tag-id),
`search` (zoekt in titel en omschrijving), `sort` (`title`, `posted_at`, `priority`, `scraped_at`),
`direction` (`asc`/`desc`), `per_page` (max 100), `page`.

Body voor POST/PATCH: `title` (verplicht bij POST), `description`, `source_url` (uniek), `client_id`,
`contact_person_id`, `status` (`new`, `in_review`, `accepted`, `rejected`, `assigned`),
`priority` (`high`, `medium`, `low`), `posted_at`, `tags` (array met tag-id's, vervangt de huidige tags).

Fouten: `404` bestaat niet, `422` validatie mislukt.

## Assignment claims

| Methode | URL | Doel |
|---|---|---|
| GET | `/api/assignment-claims` | lijst (gepagineerd), filters: `status`, `user_id`, `contractor_id`, `per_page` |
| GET | `/api/assignment-claims/{id}` | één claim met assignment, user en contractor |
| POST | `/api/assignment-claims` | assignment claimen |
| PATCH | `/api/assignment-claims/{id}` | claim wijzigen / toewijzen aan contractor |
| DELETE | `/api/assignment-claims/{id}` | claim intrekken |

Body voor POST: `assignment_id` (verplicht, max één claim per assignment), `user_id` (verplicht),
`contractor_id`, `status` (`claimed`, `assigned`, `completed`; standaard `claimed`).
Body voor PATCH: `user_id`, `contractor_id`, `status`.

Regels:
- `status: assigned` kan alleen met een `contractor_id`; `assigned_at` wordt dan automatisch gezet.
- Bij `assigned` of `completed` wordt de status van de assignment ook `assigned`.
- Bij verwijderen van de claim gaat een `assigned` assignment terug naar `accepted`.

Fouten: `404` bestaat niet, `422` validatie mislukt (o.a. assignment al geclaimd).

## Assignment tags

| Methode | URL | Doel |
|---|---|---|
| GET | `/api/assignments/{id}/tags` | alle tags van een assignment |
| POST | `/api/assignments/{id}/tags` | tag(s) koppelen aan een assignment |
| DELETE | `/api/assignments/{id}/tags/{tag_id}` | tag ontkoppelen |

Body voor POST: `tag_id` (één tag) of `tags` (array met tag-id's). Bestaande tags blijven staan,
dubbele koppelingen worden genegeerd. Wil je alle tags in één keer vervangen, gebruik dan
`tags` bij `PATCH /api/assignments/{id}`.

Fouten: `404` assignment/tag bestaat niet of tag is niet gekoppeld, `422` validatie mislukt.

## Nog te doen

Client, ClientTag, ContactPerson, Contractor, ContractorTag, Keywords, Sources, Tags.
