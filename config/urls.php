<?php

return [
    // viewer
    '/' => 'viewer/index',
    'stare-at/<pdfName>' => 'viewer/index',
    // library
    'library' => 'library/index',
    'upload-pdf' => 'library/upload-pdf',
    // identity
    'logout' => 'identity/logout',
    'login' => 'identity/login-form',
    'send-credentials-to-login' => 'identity/send-login-form',

];