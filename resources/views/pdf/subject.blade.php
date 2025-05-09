<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .material { margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>{{ $subject->name }}</h1>
    <hr>
    @foreach ($subject->materials as $material)
        <div class="material">
            <h3>{{ $material->name }}</h3>
            <p>{{ $material->content }}</p>
        </div>
    @endforeach
</body>
</html>
