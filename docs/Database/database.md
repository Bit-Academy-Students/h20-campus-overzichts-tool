# Database

Documentatie van de database van de Campus Lead Management Tool (portfolio-examen).

- **Database:** MySQL (InnoDB, `utf8mb4`)
- **Bestand:** `structure.sql` maakt alle tabellen aan
- **Importeren:** het script gebruikt `CREATE TABLE IF NOT EXISTS`, dus het kan veilig opnieuw worden uitgevoerd

## Hoe het werkt

1. Opdrachten worden van internet gehaald (via bronnen en keywords) en in de database opgeslagen.
2. Een gemeente-medewerker met een account logt in en **claimt** een opdracht.
3. De medewerker **wijst** die opdracht daarna **toe aan een bedrijf** dat hem kan oplossen.
4. Tags op opdrachten en bedrijven helpen om te zien welk bedrijf bij welke opdracht past.

Een opdracht zonder rij in `assignment_claims` is nog **open**.

## Overzicht van de relaties

```
assignments --- assignment_tags --- tags --- company_tags --- companies
    |                                                          |
    +-------------- assignment_claims ---------------------+
                            |
                        users

keywords   (los)
sources    (los)
```

## Tabellen

### `users`
Gebruikers met een login (gemeente-medewerkers en admins).

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `first_name`, `last_name` | Naam |
| `email` | Uniek, wordt gebruikt om in te loggen |
| `password` | Gehashte versie van het wachtwoord (nooit het wachtwoord zelf) |
| `role` | `municipality` of `admin` |
| `created_at` | Aanmaakmoment |

### `companies`
Bedrijven die opdrachten kunnen oplossen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `name` | Naam van het bedrijf |
| `description` | Wat het bedrijf doet |
| `website`, `email`, `phone` | Contactgegevens |
| `address`, `city` | Locatie |
| `created_at` | Aanmaakmoment |

### `assignments`
Opdrachten die van internet zijn gehaald.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `title` | Titel van de opdracht |
| `description` | Omschrijving |
| `source_url` | Link naar de originele opdracht (uniek, zodat dezelfde opdracht niet dubbel wordt opgeslagen) |
| `posted_at` | Datum waarop de opdracht online kwam |
| `scraped_at` | Moment waarop de opdracht is opgehaald |

### `assignment_claims`
Hier legt een gemeente-medewerker vast dat hij een opdracht claimt en aan welk bedrijf hij hem geeft.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `assignment_id` | De opdracht (uniek: een opdracht kan maar een keer geclaimd worden) |
| `user_id` | De medewerker die de opdracht claimde (`users`) |
| `company_id` | Het bedrijf dat de opdracht oplost. Leeg betekent geclaimd maar nog niet toegewezen |
| `status` | `claimed`, `assigned` of `completed` |
| `claimed_at` | Moment van claimen |
| `assigned_at` | Moment van toewijzen aan een bedrijf |

### `tags`
Labels (bijvoorbeeld `webdevelopment`, `marketing`) waarmee bedrijven en opdrachten worden gekoppeld.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `name` | Naam van de tag (uniek) |

### `company_tags` en `assignment_tags`
Koppeltabellen voor een veel-op-veel-relatie: een bedrijf of opdracht kan meerdere tags hebben en een tag kan bij meerdere bedrijven of opdrachten horen.

| Kolom | Beschrijving |
|---|---|
| `company_id` / `assignment_id` | Het bedrijf of de opdracht |
| `tag_id` | De tag |

### `keywords`
Losse tabel met zoektermen om opdrachten mee te vinden. Heeft geen koppeling met andere tabellen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `word` | Het zoekwoord (uniek) |

### `sources`
Losse tabel met websites waar opdrachten vandaan komen. Heeft geen koppeling met andere tabellen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `name` | Naam van de bron |
| `url` | Adres van de bron (uniek) |
| `active` | `1` = wordt gebruikt, `0` = uitgeschakeld |

## Verwijderregels

- Een opdracht verwijderen verwijdert ook zijn toewijzing en zijn tags.
- Een bedrijf verwijderen verwijdert zijn tags. Toewijzingen blijven bestaan, maar `company_id` wordt leeg.
- Een tag verwijderen verwijdert de koppelingen met bedrijven en opdrachten.
- Een persoon die een opdracht heeft geclaimd kan niet worden verwijderd zolang die toewijzing bestaat.

## Handige queries

Alle open opdrachten:

```sql
SELECT o.*
FROM assignments o
LEFT JOIN assignment_claims t ON t.assignment_id = o.id
WHERE t.id IS NULL;
```

Bedrijven die passen bij een opdracht (via gedeelde tags):

```sql
SELECT b.id, b.name, COUNT(*) AS shared_tags
FROM assignment_tags ot
JOIN company_tags bt ON bt.tag_id = ot.tag_id
JOIN companies b ON b.id = bt.company_id
WHERE ot.assignment_id = 1
GROUP BY b.id, b.name
ORDER BY shared_tags DESC;
```

Opdrachten die een medewerker heeft geclaimd:

```sql
SELECT o.title, t.status, b.name AS company
FROM assignment_claims t
JOIN assignments o ON o.id = t.assignment_id
LEFT JOIN companies b ON b.id = t.company_id
WHERE t.user_id = 1;
```