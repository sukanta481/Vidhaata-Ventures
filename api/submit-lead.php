<?php
header('Content-Type: application/json; charset=utf-8');

function respond(bool $success, string $message = '', int $statusCode = 200): void
{
  http_response_code($statusCode);
  echo json_encode([
    'success' => $success,
    'message' => $message,
  ]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  respond(false, 'Only POST requests are allowed.', 405);
}

$name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''), ENT_QUOTES, 'UTF-8');
$email = htmlspecialchars(trim($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(trim($_POST['message'] ?? ''), ENT_QUOTES, 'UTF-8');
$sourcePage = htmlspecialchars(trim($_POST['source_page'] ?? ''), ENT_QUOTES, 'UTF-8');

if ($name === '' || $phone === '') {
  respond(false, 'Name and phone number are required.', 422);
}

if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
  respond(false, 'Please enter a valid email address.', 422);
}

try {
  require_once __DIR__ . '/../includes/db.php';
} catch (Throwable $e) {
  respond(false, 'Database connection is unavailable.', 500);
}

try {
  $stmt = $pdo->prepare(
    'INSERT INTO leads (name, phone, email, message, source_page)
     VALUES (:name, :phone, :email, :message, :source_page)'
  );

  $stmt->execute([
    ':name' => $name,
    ':phone' => $phone,
    ':email' => $email !== '' ? $email : null,
    ':message' => $message !== '' ? $message : null,
    ':source_page' => $sourcePage !== '' ? $sourcePage : null,
  ]);

  respond(true);
} catch (PDOException $e) {
  respond(false, 'Unable to submit your request right now.', 500);
}
