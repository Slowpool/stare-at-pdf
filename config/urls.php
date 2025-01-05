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
        // here pdfName doesn't have extension. file *semaphores.pdf* has to be specified as *stare-at/semaphores*
        'pattern' => 'stare-at/<pdfName>',
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
