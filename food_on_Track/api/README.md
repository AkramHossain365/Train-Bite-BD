# RailBites BD — Backend Setup Guide (PHP + MySQL)

This folder holds the PHP API that connects the front-end (`index.html` + `js/main.js`)
to a real MySQL database. The front-end used to keep users/orders in browser memory;
now it talks to these endpoints.

---

## 1. What each file does

| File | Purpose |
|------|---------|
| `database.sql` | Creates the `railbites_bd` database and all tables. Run once. |
| `db.php` | PDO connection + shared JSON helpers. Every endpoint includes this. |
| `register.php` | `POST` → create user (`name`, `phone`, `email`, `gender`, `age`, `nid`, `address`, `default_station`, `password`). Password is hashed. |
| `login.php` | `POST` → verify `phone` + `password`. Returns the user object (incl. profile fields). |
| `create_order.php` | `POST` → save an order + its items, returns `order_code`. |
| `get_orders.php` | `GET ?user_id=` → that user's order history (newest first). |
| `contact.php` | `POST` → save a contact-form message. |
| `trains.php` | `GET ?q=` / `?no=` / `?station=` → search the Bangladesh Railway schedule. |
| `train_data.php` | The train schedule + search helpers used by `trains.php`. |
| `seed.php` | Optional one-time script: creates a test account. |
| `migrate.php` | Optional upgrade helper: adds the profile columns to an older `users` table. |

---

## 2. Prerequisites

Install **XAMPP** (or WAMP/Laragon) — it bundles Apache, PHP and MySQL.
Default assumption in these files: MySQL user `root`, empty password, port `3306`.

---

## 3. Step-by-step

1. **Copy the project into the web root.**
   Put the folder so that it is served by Apache, e.g.
   `C:\xampp\htdocs\food-on-track\forntend\`.

2. **Start Apache + MySQL** in the XAMPP Control Panel.

3. **Create the database.**
   - Open <http://localhost/phpmyadmin>
   - Click **Import** → choose `api/database.sql` → **Go**.
   - (CLI alternative: `mysql -u root < api/database.sql`)

4. **Check your DB credentials** in `api/db.php`:
   ```php
   define('DB_USER', 'root');
   define('DB_PASS', '');   // set your MySQL password if you have one
   ```

5. **(Optional) Create the test account.**
   Visit <http://localhost/food-on-track/forntend/api/seed.php>
   → login with **01712345678 / 123**.
   (Or simply register a new account from the website.)

5b. **Upgrading an existing database?**
   If you created the `users` table *before* the passenger-profile fields existed,
   open <http://localhost/food-on-track/forntend/api/migrate.php> once to add the
   missing columns. A fresh import of `database.sql` already includes them.

6. **Open the site:**
   <http://localhost/food-on-track/forntend/index.html>

> Important: always open the site through `http://localhost/...`, **not** by
> double-clicking `index.html`. The `fetch()` calls to the PHP API need Apache.

---

## 4. API reference

All endpoints return JSON. On success: `{ "success": true, ... }`.
On failure: `{ "success": false, "message": "..." }`.

### register.php
```json
POST {
  "name": "Rakib Hasan", "phone": "01712345678",
  "email": "rakib@example.com", "gender": "male", "age": "28",
  "nid": "1234567890123", "address": "Mirpur, Dhaka",
  "default_station": "Biman Bandar (Airport) Station",
  "password": "secret123"
}
→ { "success": true, "message": "...", "user": { "id": 1, "name": "Rakib Hasan", "phone": "01712345678", ... } }
```
Only `name`, `phone` and `password` are required; the rest are optional.
`phone` must match `01[3-9]XXXXXXXX`, `password` must be ≥ 6 characters.

### login.php
```json
POST { "phone": "01712345678", "password": "secret123" }
→ { "success": true, "user": { "id": 1, "name": "...", "phone": "...", "email": "...", "gender": "...", "age": 28, "default_station": "..." } }
```

### create_order.php
```json
POST {
  "user_id": 1,
  "items": [ { "food_id": 1, "title": "Kacchi", "price": 280, "qty": 2 } ],
  "station": "Biman Bandar (Airport) Station",
  "pnr": "ABC123", "coach": "S_CHAIR", "seat": "45", "payment_method": "cod"
}
→ { "success": true, "order": { "order_code": "BD-123456", "total": 560, "station": "..." } }
```

### get_orders.php
```
GET get_orders.php?user_id=1
→ { "success": true, "orders": [ { "order_code": "BD-123456", "items": "...", "total": 560, "station": "...", "status": "On the way" } ] }
```

### trains.php
```
GET trains.php?q=suborno      -> matches train number, name, origin, destination
GET trains.php?q=৭০১          -> Bengali numerals work (normalised to 701)
GET trains.php?no=701         -> exactly one train
GET trains.php?station=Sylhet -> all trains whose route stops at Sylhet
GET trains.php                -> the full schedule (64 trains)

→ { "success": true, "count": 2, "trains": [
      { "no": 701, "name": "Suborno Express", "from": "Chattogram",
        "departure": "07:00", "to": "Dhaka", "arrival": "11:55",
        "off_day": "Monday",
        "route": ["Chattogram", "Feni", ... "Dhaka"] } ] }
```
An empty time means the source schedule has no published time; the UI shows `—`.

### contact.php
```json
POST { "name": "X", "contact": "01...", "subject": "Hi", "message": "..." }
→ { "success": true, "message": "..." }
```

---

## 4b. Train info search (front-end)

The hero has a single search box (**Find Train**) — its input is fed by
`#train-input` and the surrounding `#search-form`, so plain Enter works too.
A row of quick-pick chips (**জনপ্রিয়**) sits directly beneath it, and results
appear in the panel below. It calls `trains.php` and renders, for each match:

- train number + name, and the **weekly off day** (red = runs most days,
  green = no off day)
- **From → To** with departure and arrival times
- the full **route / stoppage list**, with an **estimated arrival time (ETA)**
  beside every stop, and the origin/destination marked
- an **“এই ট্রেনে খাবার অর্ডার করুন”** button that reveals the stoppage picker
Search accepts a **train number**, **name**, or **station name**, and Bengali
numerals (`৭০১`) are converted to ASCII automatically.

### Choosing a delivery station (stoppage picker)

Selecting a train opens `#station-selector-box`, which lists **that train's own
stoppages** instead of a fixed station list. Rules:

- the **departure station is excluded** (the train has just left — food cannot
  be served aboard yet); every stop **between departure and destination** is
  offered, including the destination itself
- each option shows an **estimated arrival time**; the destination is
  pre-selected so a choice is always made
- the chosen stoppage is what goes into the order's `station` field
**How the ETAs are derived** (`routeWithEtas()` in `js/main.js`): the schedule
only publishes the origin *departure* and the destination *arrival*, so the
total journey time is spread evenly across the stops in between, i.e.
`departure + (journeyMinutes × stopIndex / (stops − 1))`.
- Journeys that **cross midnight** are handled: when the arrival clock time is
  numerically less than the departure time, 1440 minutes is added, and every
  ETA is wrapped back into `00:00–23:59`. *(#722 departs 21:20 and arrives
  06:40 — intermediate stops correctly read 00:27, 02:00, …)*
- Trains with **no published times** (e.g. #729 Titas Commuter) show `—`
  instead of a bogus time.

> These ETAs are interpolated from the published endpoints and are an
> approximation — real running times vary per stop. Swap in per-stop times in
> `api/train_data.php` (add a `times` array to `route`) if exact figures are
> needed later.

---

## 5. Security notes (for later)

- Passwords are stored with `password_hash()` and verified with `password_verify()` — never in plain text.
- All SQL uses **prepared statements** (safe from SQL injection).
- `db.php` currently sends `Access-Control-Allow-Origin: *` (fine for local dev).
  Restrict it to your own domain in production.
- Consider adding PHP sessions/JWT so `user_id` cannot be spoofed by the client.

---

## 6. Troubleshooting

| Symptom | Fix |
|---------|-----|
| `Database connection failed` | MySQL not running, or wrong credentials in `db.php`. |
| `404` on `api/*.php` | You opened `index.html` directly. Use `http://localhost/...`. |
| `Unknown database 'railbites_bd'` | Import `database.sql` in phpMyAdmin. |
| `Call to undefined function db()` | PHP file can't find `db.php` — keep all files in the same `api/` folder. |
| Bengali text shows as `????` | Ensure the DB/tables use `utf8mb4` (already set in `database.sql`). |
