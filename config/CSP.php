<?php
return
    [
        'class' => 'hyperia\security\Headers',
        'xFrameOptions' => 'ALLOW', // actually it could be any value except DENY
        'cspDirectives' => [
            'default-src' => "'self'",
            'style-src' => "'self' 'unsafe-inline'", // for category colors of pdf cards
            // 'object-src' => "'self'",
            // 'script-src' => "'self'",
            // 'img-src' => "'self'",
            // 'connect-src' => "'self'",
            // 'font-src' => "'self'",
            // 'media-src' => "'self'",
            // 'form-action' => "'self'",
            // 'frame-src' => "'self'",
            // 'child-src' => "'self'",
        ],
    ];