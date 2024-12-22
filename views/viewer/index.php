<?php

/** @var yii\web\View $this */
/** @var $bookmark must not be null */

?>
<div class="pdf-viewer-container">
    <?php
    $pdfUrl = $pdfSpecified ? "uploads/" . Yii::$app->user->identity->name . "/$pdfName.pdf#page=$bookmark" : '';
    ?>

    <?= \diecoding\pdfjs\PdfJs::widget([
        // 'url' => $pdfUrl,
        'url' => '/uploads/slowpool/polish_A1.pdf#page=80',
        // 'encodeUrl' => false,
        'options' => [
            'id' => 'pdf-viewer-widget',
            'style' => [
                'width' => '100%',
                'height' => '100%',
            ],
            // // this doesn't work, but supposed to
            // 'enableClientValidation' => false,
            // 'openWithFragmentIdentifier' => 'true',
            // 'disableAutoFetch' => 'true',
        ],
    ]); ?>
</div>