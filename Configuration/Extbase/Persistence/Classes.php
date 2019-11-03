<?php
declare(strict_types = 1);

return [
    \GeorgRinger\News\Domain\Model\FileReference::class => [
        'tableName' => 'sys_file_reference',
    ],
    \GeorgRinger\News\Domain\Model\TtContent::class => [
        'tableName' => 'tt_content',
    ],
    \GeorgRinger\News\Domain\Model\Category::class => [
        'tableName' => 'sys_category',
        'properties' => [
            'parentcategory' => [
                'fieldName' => 'parent'
            ]
        ]
    ],
];
