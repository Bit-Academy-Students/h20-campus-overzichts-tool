# Database

Documentatie van de database van de Campus Lead Management Tool (portfolio-examen).

- **Database:** MySQL (InnoDB, `utf8mb4`)
- **Bestand:** `database.sql` maakt alle tabellen aan
- **Importeren:** het script gebruikt `CREATE TABLE IF NOT EXISTS`, dus het kan veilig opnieuw worden uitgevoerd

## Hoe het werkt

1. Opdrachten worden van internet gehaald (via bronnen en keywords) en in de database opgeslagen.
2. Een gemeente-medewerker met een account logt in en **claimt** een opdracht.
3. De medewerker **wijst** die opdracht daarna **toe aan een bedrijf** dat hem kan oplossen.
4. Tags op opdrachten en bedrijven helpen om te zien welk bedrijf bij welke opdracht past.

Een opdracht zonder rij in `opdracht_toewijzingen` is nog **open**.

## Overzicht van de relaties

```
opdrachten --- opdracht_tags --- tags --- bedrijf_tags --- bedrijven
    |                                                          |
    +-------------- opdracht_toewijzingen ---------------------+
                            |
                        personen

keywords   (los)
bronnen    (los)
```

## Tabellen

### `personen`
Gebruikers met een login (gemeente-medewerkers en admins).

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `voornaam`, `achternaam` | Naam |
| `email` | Uniek, wordt gebruikt om in te loggen |
| `wachtwoord_hash` | Gehashte versie van het wachtwoord (nooit het wachtwoord zelf) |
| `rol` | `gemeente` of `admin` |
| `created_at` | Aanmaakmoment |

### `bedrijven`
Bedrijven die opdrachten kunnen oplossen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `naam` | Naam van het bedrijf |
| `beschrijving` | Wat het bedrijf doet |
| `website`, `email`, `telefoon` | Contactgegevens |
| `adres`, `plaats` | Locatie |
| `created_at` | Aanmaakmoment |

### `opdrachten`
Opdrachten die van internet zijn gehaald.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `titel` | Titel van de opdracht |
| `beschrijving` | Omschrijving |
| `bron_url` | Link naar de originele opdracht (uniek, zodat dezelfde opdracht niet dubbel wordt opgeslagen) |
| `geplaatst_op` | Datum waarop de opdracht online kwam |
| `scraped_at` | Moment waarop de opdracht is opgehaald |

### `opdracht_toewijzingen`
Hier legt een gemeente-medewerker vast dat hij een opdracht claimt en aan welk bedrijf hij hem geeft.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `opdracht_id` | De opdracht (uniek: een opdracht kan maar een keer geclaimd worden) |
| `gemeente_persoon_id` | De medewerker die de opdracht claimde (`personen`) |
| `bedrijf_id` | Het bedrijf dat de opdracht oplost. Leeg betekent geclaimd maar nog niet toegewezen |
| `status` | `geclaimd`, `toegewezen` of `afgerond` |
| `geclaimd_op` | Moment van claimen |
| `toegewezen_op` | Moment van toewijzen aan een bedrijf |

### `tags`
Labels (bijvoorbeeld `webdevelopment`, `marketing`) waarmee bedrijven en opdrachten worden gekoppeld.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `naam` | Naam van de tag (uniek) |

### `bedrijf_tags` en `opdracht_tags`
Koppeltabellen voor een veel-op-veel-relatie: een bedrijf of opdracht kan meerdere tags hebben en een tag kan bij meerdere bedrijven of opdrachten horen.

| Kolom | Beschrijving |
|---|---|
| `bedrijf_id` / `opdracht_id` | Het bedrijf of de opdracht |
| `tag_id` | De tag |

### `keywords`
Losse tabel met zoektermen om opdrachten mee te vinden. Heeft geen koppeling met andere tabellen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `woord` | Het zoekwoord (uniek) |

### `bronnen`
Losse tabel met websites waar opdrachten vandaan komen. Heeft geen koppeling met andere tabellen.

| Kolom | Beschrijving |
|---|---|
| `id` | Primaire sleutel |
| `naam` | Naam van de bron |
| `url` | Adres van de bron (uniek) |
| `actief` | `1` = wordt gebruikt, `0` = uitgeschakeld |

## Verwijderregels

- Een opdracht verwijderen verwijdert ook zijn toewijzing en zijn tags.
- Een bedrijf verwijderen verwijdert zijn tags. Toewijzingen blijven bestaan, maar `bedrijf_id` wordt leeg.
- Een tag verwijderen verwijdert de koppelingen met bedrijven en opdrachten.
- Een persoon die een opdracht heeft geclaimd kan niet worden verwijderd zolang die toewijzing bestaat.

## Handige queries

Alle open opdrachten:

```sql
SELECT o.*
FROM opdrachten o
LEFT JOIN opdracht_toewijzingen t ON t.opdracht_id = o.id
WHERE t.id IS NULL;
```

Bedrijven die passen bij een opdracht (via gedeelde tags):

```sql
SELECT b.id, b.naam, COUNT(*) AS gedeelde_tags
FROM opdracht_tags ot
JOIN bedrijf_tags bt ON bt.tag_id = ot.tag_id
JOIN bedrijven b ON b.id = bt.bedrijf_id
WHERE ot.opdracht_id = 1
GROUP BY b.id, b.naam
ORDER BY gedeelde_tags DESC;
```

Opdrachten die een medewerker heeft geclaimd:

```sql
SELECT o.titel, t.status, b.naam AS bedrijf
FROM opdracht_toewijzingen t
JOIN opdrachten o ON o.id = t.opdracht_id
LEFT JOIN bedrijven b ON b.id = t.bedrijf_id
WHERE t.gemeente_persoon_id = 1;
```