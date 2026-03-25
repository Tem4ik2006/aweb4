<?php
// Настройки подключения к БД
$db_user = 'u82321';
$db_pass = '3538664';   
$db_name = 'u82321';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("
        SELECT a.*, GROUP_CONCAT(l.name SEPARATOR ', ') AS languages
        FROM application a
        LEFT JOIN application_language al ON a.id = al.application_id
        LEFT JOIN language l ON al.language_id = l.id
        GROUP BY a.id
        ORDER BY a.id DESC
    ");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Ошибка: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сохранённые анкеты | Лабораторная работа №4</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f5f7fa;
            color: #2c3e50;
        }
        tr:hover td {
            background-color: #f9f9ff;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            text-decoration: none;
            display: inline-block;
        }
        .back-link a:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📊 Сохранённые анкеты</h1>
        <p>Всего записей: <?= count($applications) ?></p>
    </div>

    <div class="form-container" style="padding: 20px;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ФИО</th>
                    <th>Телефон</th>
                    <th>Email</th>
                    <th>Дата рождения</th>
                    <th>Пол</th>
                    <th>Биография</th>
                    <th>Согласие</th>
                    <th>Языки</th>
                    <th>Дата создания</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                <tr>
                    <td><?= htmlspecialchars($app['id']) ?></td>
                    <td><?= htmlspecialchars($app['full_name']) ?></td>
                    <td><?= htmlspecialchars($app['phone']) ?></td>
                    <td><?= htmlspecialchars($app['email']) ?></td>
                    <td><?= htmlspecialchars($app['birth_date']) ?></td>
                    <td><?= $app['gender'] === 'male' ? 'Мужской' : 'Женский' ?></td>
                    <td><?= nl2br(htmlspecialchars($app['biography'])) ?></td>
                    <td><?= $app['contract_accepted'] ? '✅ Да' : '❌ Нет' ?></td>
                    <td><?= htmlspecialchars($app['languages']) ?></td>
                    <td><?= htmlspecialchars($app['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
         </table>

        <div class="back-link">
            <a href="index.php">← Вернуться к форме</a>
        </div>
    </div>
</div>
</body>
</html>