SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS users (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  first_name  VARCHAR(100) NOT NULL,
  last_name   VARCHAR(100) NOT NULL,
  email       VARCHAR(255) NOT NULL,
  password    VARCHAR(255) NOT NULL,
  role        ENUM('municipality', 'admin') NOT NULL DEFAULT 'municipality',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdrachtnemers (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name         VARCHAR(150) NOT NULL,
  description  TEXT NULL,
  website      VARCHAR(255) NULL,
  email        VARCHAR(255) NULL,
  phone        VARCHAR(30) NULL,
  address      VARCHAR(255) NULL,
  city         VARCHAR(100) NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdrachtgevers (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name         VARCHAR(150) NOT NULL,
  description  TEXT NULL,
  website      VARCHAR(255) NULL,
  email        VARCHAR(255) NULL,
  phone        VARCHAR(30) NULL,
  address      VARCHAR(255) NULL,
  city         VARCHAR(100) NULL,
  created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS contactpersonen (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  opdrachtgever_id  INT UNSIGNED NOT NULL,
  first_name        VARCHAR(100) NOT NULL,
  last_name         VARCHAR(100) NOT NULL,
  function          VARCHAR(100) NULL,
  email             VARCHAR(255) NULL,
  phone             VARCHAR(30) NULL,
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_contactpersonen_opdrachtgever (opdrachtgever_id),
  CONSTRAINT fk_contactpersonen_opdrachtgever
    FOREIGN KEY (opdrachtgever_id) REFERENCES opdrachtgevers (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assignments (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  opdrachtgever_id INT UNSIGNED NULL,
  title        VARCHAR(200) NOT NULL,
  description  TEXT NULL,
  source_url   VARCHAR(500) NULL,
  posted_at    DATE NULL,
  scraped_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_assignments_source_url (source_url),
  KEY idx_assignments_opdrachtgever (opdrachtgever_id),
  CONSTRAINT fk_assignments_opdrachtgever
    FOREIGN KEY (opdrachtgever_id) REFERENCES opdrachtgevers (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tags (
  id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name  VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_tags_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS keywords (
  id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
  word  VARCHAR(50) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_keywords_word (word)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sources (
  id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name    VARCHAR(100) NOT NULL,
  url     VARCHAR(500) NOT NULL,
  active  TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (id),
  UNIQUE KEY uq_sources_url (url)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assignment_claims (
  id             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  assignment_id  INT UNSIGNED NOT NULL,
  user_id        INT UNSIGNED NOT NULL,
  opdrachtnemer_id     INT UNSIGNED NULL,
  status         ENUM('claimed', 'assigned', 'completed') NOT NULL DEFAULT 'claimed',
  claimed_at     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  assigned_at    TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_claims_assignment (assignment_id),
  KEY idx_claims_user (user_id),
  KEY idx_claims_opdrachtnemer (opdrachtnemer_id),
  CONSTRAINT fk_claims_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments (id) ON DELETE CASCADE,
  CONSTRAINT fk_claims_user
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT fk_claims_opdrachtnemer
    FOREIGN KEY (opdrachtnemer_id) REFERENCES opdrachtnemers (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS opdrachtnemer_tags (
  opdrachtnemer_id  INT UNSIGNED NOT NULL,
  tag_id      INT UNSIGNED NOT NULL,
  PRIMARY KEY (opdrachtnemer_id, tag_id),
  KEY idx_opdrachtnemer_tags_tag (tag_id),
  CONSTRAINT fk_opdrachtnemer_tags_opdrachtnemer
    FOREIGN KEY (opdrachtnemer_id) REFERENCES opdrachtnemers (id) ON DELETE CASCADE,
  CONSTRAINT fk_opdrachtnemer_tags_tag
    FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assignment_tags (
  assignment_id  INT UNSIGNED NOT NULL,
  tag_id         INT UNSIGNED NOT NULL,
  PRIMARY KEY (assignment_id, tag_id),
  KEY idx_assignment_tags_tag (tag_id),
  CONSTRAINT fk_assignment_tags_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments (id) ON DELETE CASCADE,
  CONSTRAINT fk_assignment_tags_tag
    FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS assignment_favorites (
  user_id        INT UNSIGNED NOT NULL,
  assignment_id  INT UNSIGNED NOT NULL,
  PRIMARY KEY (user_id, assignment_id),
  KEY idx_assignment_favorites_assignment (assignment_id),
  CONSTRAINT fk_assignment_favorites_user
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
  CONSTRAINT fk_assignment_favorites_assignment
    FOREIGN KEY (assignment_id) REFERENCES assignments (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
