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

Rollen: `pending` (wacht op admin), `municipality`, `admin`. Het wachtwoord zit nooit in de JSON.

```js
// alle users
fetch('/api/users').then(r => r.json()).then(console.log);

// aanmaken
fetch('/api/users', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ first_name: 'Piet', last_name: 'Pietersen', email: 'piet@test.nl', password: 'geheim1234' }),
});

// goedkeuren
fetch('/api/users/2', {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
    body: JSON.stringify({ role: 'municipality' }),
});

// verwijderen
fetch('/api/users/2', { method: 'DELETE', headers: { 'Accept': 'application/json' } });
```

Fouten: `404` bestaat niet, `422` validatie mislukt.

## Nog te doen

Assignments, AssignmentClaim, Assignment_Tags, Client, ClientTag, ContactPerson, Contractor, ContractorTag, Keywords, Sources, Tags.
