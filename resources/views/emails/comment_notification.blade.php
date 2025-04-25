<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новий коментар</title>
</head>
<body>
    <h2>Новий коментар на вашу статтю</h2>
    <p>Вас залишили коментар до вашої статті:</p>

    <p><strong>Автор:</strong> {{ $comment->user->name }}</p>
    <p><strong>Текст коментаря:</strong></p>
    <p>{{ $comment->body }}</p>

    <p>Щоб переглянути коментар, перейдіть за посиланням: <a href="{{ route('news.show', $comment->news_id) }}">Переглянути статтю</a></p>
</body>
</html>
