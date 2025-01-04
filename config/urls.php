<?php

return [
    // viewer
    [
        'pattern' => '/',
        'route' => 'viewer/index',
        'defaults' => ['pdfName' => null]
    ],
    'update-bookmark' => 'viewer/update-bookmark',
    // here pdfName doesn't have extension. file *semaphores.pdf* has to be specified as *stare-at/semaphores*
    [
        // TODO point in url after stare-at/ displays Page not found
        'pattern' => 'stare-at/<pdfName:.+>',
        'route' => 'viewer/index',
        // 'encoreUrl' => 'false',
    ],
    // library
    'library' => 'library/index',
    'upload-pdf' => 'library/upload-pdf',
    'add-new-category' => 'library/add-new-category',
    // identity
    'logout' => 'identity/logout',
    'login' => 'identity/login-form',
    'send-credentials-to-login' => 'identity/send-login-form',
];
