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
    App\Repositories\Contracts\NewsRepositoryInterface::class => [
        'core' => App\Repositories\Core\NewsRepository::class,
        'decorators' => [
            App\Repositories\Decorators\LoggingNewsRepositoryDecorator::class,
        ],
    ],
    App\Repositories\Contracts\CommentRepositoryInterface::class => [
        'core' => App\Repositories\Core\CommentRepository::class,
        'decorators' => [
            App\Repositories\Decorators\LoggingCommentRepositoryDecorator::class,
        ],
    ],  
    
];
