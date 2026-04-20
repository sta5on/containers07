<?php
declare(strict_types=1);

$appVersion = getenv('APP_VERSION') ?: '1.0.0';
$dbHost = getenv('DB_HOST') ?: 'database';
$dbName = getenv('MYSQL_DATABASE') ?: 'app';
$dbUser = getenv('MYSQL_USER') ?: 'user';
$dbPassword = getenv('MYSQL_PASSWORD') ?: 'secret';

$dbStatus = 'Connected';
$dbError = null;
$dbVersion = null;

try {
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $dbHost, $dbName);
    $pdo = new PDO($dsn, $dbUser, $dbPassword, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $dbVersion = (string) $pdo->query('SELECT VERSION()')->fetchColumn();
} catch (PDOException $exception) {
    $dbStatus = 'Connection failed';
    $dbError = $exception->getMessage();
}

$cards = [
    'Application' => 'containers07 laboratory project',
    'App version' => $appVersion,
    'PHP version' => PHP_VERSION,
    'Database host' => $dbHost,
    'Database name' => $dbName,
    'Database status' => $dbStatus,
];

if ($dbVersion !== null) {
    $cards['Database version'] = $dbVersion;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>containers07</title>
</head>
<body>
    <h1>Лабораторная работа 7</h1>
    <p>Многоконтейнерное приложение на Docker Compose.</p>

    <h2>Информация о приложении</h2>
    <ul>
        <?php foreach ($cards as $label => $value): ?>
            <li>
                <strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?>:</strong>
                <?= htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8') ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($dbError !== null): ?>
        <p><strong>Ошибка подключения к базе данных:</strong> <?= htmlspecialchars($dbError, ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p>Подключение к базе данных работает.</p>
    <?php endif; ?>
</body>
</html>
