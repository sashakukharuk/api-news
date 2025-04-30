<?php

return [
    App\Services\Contracts\NewsServiceInterface::class => [
        'core' => App\Services\Core\NewsService::class,
        'decorators' => [
            App\Services\Decorators\LoggingNewsServiceDecorator::class,
        ],
    ],
    App\Services\Contracts\CommentServiceInterface::class => [
        'core' => App\Services\Core\CommentService::class,
        'decorators' => [
            App\Services\Decorators\LoggingCommentServiceDecorator::class,
        ],
    ],
];
