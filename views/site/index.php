<?php

/** @var yii\web\View $this */

$this->title = 'My Yii Application';
?>
<div class="pdf-viewer-container">
    <?= \diecoding\pdfjs\PdfJs::widget([
        'url' => 'uploads\semaphores.pdf',
        'options' => [
            'style' => [
                'width' => '100%',
                'height' => '100%'
            ]
        ]
    ]); ?>
</div>