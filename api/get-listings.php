<?php
header('Content-Type: application/json; charset=utf-8');

function respondWithError(string $message, int $statusCode = 400): void
{
  http_response_code($statusCode);
  echo json_encode([
    'success' => false,
    'message' => $message,
  ]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  respondWithError('Only GET requests are allowed.', 405);
}

$type = trim($_GET['type'] ?? '');
$featured = trim($_GET['featured'] ?? '');
$limit = filter_input(INPUT_GET, 'limit', FILTER_VALIDATE_INT, [
  'options' => ['default' => 12, 'min_range' => 1, 'max_range' => 50],
]);
$offset = filter_input(INPUT_GET, 'offset', FILTER_VALIDATE_INT, [
  'options' => ['default' => 0, 'min_range' => 0],
]);

if ($limit === false) {
  respondWithError('Invalid limit value.');
}

if ($offset === false) {
  respondWithError('Invalid offset value.');
}

$where = ['status = :status'];
$params = [':status' => 'active'];

if ($type !== '') {
  if (!in_array($type, ['residential', 'commercial'], true)) {
    respondWithError('Invalid listing type.');
  }

  $where[] = 'type = :type';
  $params[':type'] = $type;
}

if ($featured !== '') {
  if (!in_array($featured, ['0', '1'], true)) {
    respondWithError('Invalid featured value.');
  }

  $where[] = 'is_featured = :featured';
  $params[':featured'] = (int) $featured;
}

try {
  require_once __DIR__ . '/../includes/db.php';
} catch (Throwable $e) {
  respondWithError('Database connection is unavailable.', 500);
}

try {
  $sql = 'SELECT id, type, title, description, price, location, bedrooms, area_sqft, image_filename, is_featured, status, created_at
          FROM listings
          WHERE ' . implode(' AND ', $where) . '
          ORDER BY created_at DESC
          LIMIT :limit OFFSET :offset';

  $stmt = $pdo->prepare($sql);

  foreach ($params as $key => $value) {
    $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
    $stmt->bindValue($key, $value, $paramType);
  }

  $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
  $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
  $stmt->execute();

  echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
  respondWithError('Unable to load listings right now.', 500);
}
