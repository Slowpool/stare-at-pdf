<?php

return [
    // viewer
    [
        'pattern' => '/',
        'route' => 'viewer/index',
        'defaults' => ['pdfName' => null]
    ],
    'update-bookmark' => 'viewer/update-bookmark',
    [
        // here pdfSlug doesn't have extension. file *semaphores guide.pdf* has to be specified as *stare-at/semaphores-guide*
        'pattern' => 'stare-at/<pdfSlug>',
        'route' => 'viewer/index',
    ],
    // library
    'library' => 'library/index',
    'upload-pdf' => 'library/create-pdf-file',
    'add-new-category' => 'library/create-new-category',
    'assign-category' => 'library/create-pdf-file-category-entry',
    // identity
    'logout' => 'identity/logout',
    'login' => 'identity/login-form',
    'send-credentials-to-login' => 'identity/send-login-form',
];
