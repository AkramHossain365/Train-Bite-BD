-- ============================================================
--  RailBites BD - Database Schema
--  Run this file once in phpMyAdmin / MySQL CLI to create the
--  database and all tables.
--
--  Import via phpMyAdmin:  Import -> choose this file -> Go
--  Or via CLI:
--     mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS railbites_bd
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE railbites_bd;

-- ------------------------------------------------------------
--  Users (passengers)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120)  NOT NULL,
    phone         VARCHAR(20)   NOT NULL,
    email         VARCHAR(150)  DEFAULT NULL,     -- optional receipt/contact email
    gender        VARCHAR(20)   DEFAULT NULL,     -- male / female / other
    age           INT           DEFAULT NULL,
    nid           VARCHAR(30)   DEFAULT NULL,     -- National ID (optional)
    address       VARCHAR(255)  DEFAULT NULL,     -- home address
    default_station VARCHAR(150) DEFAULT NULL,    -- usual delivery station
    password      VARCHAR(255)  NOT NULL,         -- password_hash()
    created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_users_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Orders
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    order_code      VARCHAR(20)   NOT NULL,        -- e.g. BD-123456
    user_id         INT           NOT NULL,
    pnr             VARCHAR(60)   DEFAULT NULL,
    coach           VARCHAR(60)   DEFAULT NULL,
    seat            VARCHAR(30)   DEFAULT NULL,
    station         VARCHAR(150)  NOT NULL,
    payment_method  VARCHAR(20)   NOT NULL DEFAULT 'cod',
    total_amount    DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status          VARCHAR(60)   NOT NULL DEFAULT 'On the way',
    created_at      TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_orders_code (order_code),
    KEY idx_orders_user (user_id),
    CONSTRAINT fk_orders_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Order items (one row per food line in an order)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT           NOT NULL,
    food_id     INT           NOT NULL,
    title       VARCHAR(200)  NOT NULL,
    price       DECIMAL(10,2) NOT NULL,
    qty         INT           NOT NULL DEFAULT 1,
    KEY idx_items_order (order_id),
    CONSTRAINT fk_items_order
        FOREIGN KEY (order_id) REFERENCES orders (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Contact messages
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(120)  NOT NULL,
    contact     VARCHAR(150)  NOT NULL,           -- email or mobile
    subject     VARCHAR(200)  NOT NULL,
    message     TEXT          NOT NULL,
    created_at  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------------
--  Default test account
--  Uncomment the line below ONLY if you want a test user.
--  NOTE: the password must be a real password_hash() value, so it
--  is easier to create the account by either:
--    a) Registering through the website once, OR
--    b) Visiting api/seed.php  (creates 01712345678 with password "123")
-- ------------------------------------------------------------
-- (No hard-coded hash here on purpose — see api/seed.php.)
