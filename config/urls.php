<?php

return [
    // viewer
    [
        'pattern' => '/',
        'route' => 'viewer/index',
        'defaults' => ['pdf_name' => null]
    ],
    // here pdf_name doesn't have extension. file *semaphores.pdf* has to be specified as *stare-at/semaphores*
    'stare-at/<pdf_name>' => 'viewer/index',
    // library
    'library' => 'library/index',
    'upload-pdf' => 'library/upload-pdf',
    // identity
    'logout' => 'identity/logout',
    'login' => 'identity/login-form',
    'send-credentials-to-login' => 'identity/send-login-form',
    // TODO remove
    'old-version' => 'viewer/old-version',
];
