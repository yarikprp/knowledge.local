<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Приказ</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1 {
            text-align: center;
            text-transform: uppercase;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .order-number {
            text-align: right;
            margin-bottom: 20px;
        }
        .content {
            text-align: justify;
        }
        .footer {
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <h1>ПРИКАЗ</h1>
    <div class="order-number">
        № {{ $orderNumber }} от {{ $date }}
    </div>
    <div class="content">
        <p>На основании внутреннего расписания и необходимости проведения мероприятия,</p>
        <p><strong>ПРИКАЗЫВАЮ:</strong></p>
        <ul>
            <li>Назначить пользователя <strong>{{ $user }}</strong> на прохождение аттестации <strong>{{ $eventType }}</strong>.</li>
            <li>Дата проведения: <strong>{{ $date }}</strong>.</li>
            <li>Связанный тест: <strong>{{ $testName }}</strong>.</li>
            <li>Дополнительные замечания: <strong>{{ $notes }}</strong>.</li>
        </ul>
    </div>
    <div class="footer">
        <p>Руководитель организации: ____________________</p>
    </div>
</body>
</html>
