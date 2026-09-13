<?php
/**
 * db.php
 * ---------------------------------------------------------
 * Database connection (PDO) + small shared helpers used by
 * every other endpoint in this folder.
 * ---------------------------------------------------------
 */

// ---- Database credentials (change to match your XAMPP/WAMP) ----
define('DB_HOST', '127.0.0.1');
define('DB_PORT', '3306');
define('DB_NAME', 'railbites_bd');
define('DB_USER', 'root');
define('DB_PASS', '');            // XAMPP default is empty; set your MySQL password here

/**
 * Return a shared PDO connection (created once).
 */
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        json_error('Database connection failed: ' . $e->getMessage(), 500);
    }
    return $pdo;
}

// ---------------------------------------------------------
//  Response helpers
// ---------------------------------------------------------

/**
 * Send a JSON response and stop execution.
 */
function json_out($data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Send a JSON error response and stop execution.
 */
function json_error(string $message, int $status = 400): void
{
    json_out(['success' => false, 'message' => $message], $status);
}

/**
 * Send a successful JSON response.
 */
function json_ok($data = [], string $message = ''): void
{
    $payload = ['success' => true];
    if ($message !== '') {
        $payload['message'] = $message;
    }
    if (is_array($data)) {
        $payload += $data;
    }
    json_out($payload);
}

// ---------------------------------------------------------
//  Request helpers
// ---------------------------------------------------------

/**
 * Read the incoming request body as an associative array.
 * Accepts JSON (fetch with JSON body) OR classic form POST.
 */
function request_data(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (stripos($contentType, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $decoded = json_decode($raw ?: '[]', true);
        return is_array($decoded) ? $decoded : [];
    }

    // form-encoded / multipart
    return $_POST ?: [];
}

/**
 * Pull a trimmed string field from the request, or '' if missing.
 */
function field(array $data, string $key): string
{
    return isset($data[$key]) ? trim((string) $data[$key]) : '';
}

/**
 * Allow the browser (and local dev tools) to call these endpoints.
 * Handy while developing; lock this down for production.
 */
function allow_cors(): void
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}

// Allow cross-origin calls from the front-end by default.
allow_cors();
