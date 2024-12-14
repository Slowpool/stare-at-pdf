<?php

/** @var yii\web\View $this */

?>
<div class="pdf-viewer-container">
    <?= \diecoding\pdfjs\PdfJs::widget([
        'url' => 'uploads\semaphores.pdf',
        'options' => [
            'id' => 'pdf-viewer-widget',
            'style' => [
                'width' => '100%',
                'height' => '100%'
            ]
        ]
    ]); ?>
</div>