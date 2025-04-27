<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        @vite('resources/js/app.js')

    </head>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .news-header {
            background: linear-gradient(135deg, #6B73FF 0%, #000DFF 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .comment {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s;
        }
        .comment:hover {
            transform: translateY(-2px);
        }
        .comment-header {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .comment-author {
            color: #6B73FF;
            font-weight: 500;
        }
        .comment-body {
            margin: 15px 0;
            line-height: 1.6;
        }
        .comment-date {
            color: #6c757d;
            font-size: 0.9em;
        }
        .badge {
            padding: 8px 12px;
            font-weight: 500;
        }
    </style>
    <body>
        <div class="container">
            <div class="news-header text-center">
                <h1 class="display-4">Коментарі до новини</h1>
                <p class="lead">ID: 1</p>
            </div>
            
            <div id="comments"></div>
        </div>

        <!-- Bootstrap JS та Popper.js -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </body>
</html>
