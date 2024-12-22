<?php

return [
    // viewer
    [
        'pattern' => '/',
        'route' => 'viewer/index',
        'defaults' => ['pdfName' => null]
    ],
    // here pdfName doesn't have extension. file *semaphores.pdf* has to be specified as *stare-at/semaphores*
    [
        'pattern' => 'stare-at/<pdfName>',
        'route' => 'viewer/index',
        // 'encoreUrl' => 'false',
    ],
    // library
    'library' => 'library/index',
    'upload-pdf' => 'library/upload-pdf',
    // identity
    'logout' => 'identity/logout',
    'login' => 'identity/login-form',
    'send-credentials-to-login' => 'identity/send-login-form',
];
