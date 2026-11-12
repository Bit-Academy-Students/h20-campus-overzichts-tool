SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS personen (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  voornaam         VARCHAR(100) NOT NULL,
  achternaam       VARCHAR(100) NOT NULL,
  email            VARCHAR(255) NOT NULL,
  wachtwoord_hash  VARCHAR(255) NOT NULL,
  rol              ENUM('gemeente', 'admin') NOT NULL DEFAULT 'gemeente',
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_personen_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bedrijven (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam          VARCHAR(150) NOT NULL,
  beschrijving  TEXT NULL,
  website       VARCHAR(255) NULL,
  email         VARCHAR(255) NULL,
  telefoon      VARCHAR(30) NULL,
  adres         VARCHAR(255) NULL,
  plaats        VARCHAR(100) NULL,
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdrachten (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titel         VARCHAR(200) NOT NULL,
  beschrijving  TEXT NULL,
  bron_url      VARCHAR(500) NULL,
  geplaatst_op  DATE NULL,
  scraped_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_opdrachten_bron_url (bron_url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tags (
  id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam  VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tags_naam (naam)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS keywords (
  id     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  woord  VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_keywords_woord (woord)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bronnen (
  id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  naam    VARCHAR(100) NOT NULL,
  url     VARCHAR(500) NOT NULL,
  actief  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_bronnen_url (url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdracht_toewijzingen (
  id                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  opdracht_id          INT UNSIGNED NOT NULL,
  gemeente_persoon_id  INT UNSIGNED NOT NULL,
  bedrijf_id           INT UNSIGNED NULL,
  status               ENUM('geclaimd', 'toegewezen', 'afgerond') NOT NULL DEFAULT 'geclaimd',
  geclaimd_op          TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  toegewezen_op        TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_toewijzing_opdracht (opdracht_id),
  KEY idx_toewijzing_persoon (gemeente_persoon_id),
  KEY idx_toewijzing_bedrijf (bedrijf_id),
  CONSTRAINT fk_toewijzing_opdracht
    FOREIGN KEY (opdracht_id) REFERENCES opdrachten (id) ON DELETE CASCADE,
  CONSTRAINT fk_toewijzing_persoon
    FOREIGN KEY (gemeente_persoon_id) REFERENCES personen (id),
  CONSTRAINT fk_toewijzing_bedrijf
    FOREIGN KEY (bedrijf_id) REFERENCES bedrijven (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS bedrijf_tags (
  bedrijf_id  INT UNSIGNED NOT NULL,
  tag_id      INT UNSIGNED NOT NULL,
  PRIMARY KEY (bedrijf_id, tag_id),
  KEY idx_bedrijf_tags_tag (tag_id),
  CONSTRAINT fk_bedrijf_tags_bedrijf
    FOREIGN KEY (bedrijf_id) REFERENCES bedrijven (id) ON DELETE CASCADE,
  CONSTRAINT fk_bedrijf_tags_tag
    FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdracht_tags (
  opdracht_id  INT UNSIGNED NOT NULL,
  tag_id       INT UNSIGNED NOT NULL,
  PRIMARY KEY (opdracht_id, tag_id),
  KEY idx_opdracht_tags_tag (tag_id),
  CONSTRAINT fk_opdracht_tags_opdracht
    FOREIGN KEY (opdracht_id) REFERENCES opdrachten (id) ON DELETE CASCADE,
  CONSTRAINT fk_opdracht_tags_tag
    FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;