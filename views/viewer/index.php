<?php

/** @var yii\web\View $this */
/** @var $bookmark must not be null */

?>
<div class="pdf-viewer-container">
    <?php
    $pdfUrl = $pdfSpecified ? "uploads/" . Yii::$app->user->identity->name . "/$pdfName.pdf#page=$bookmark" : '';
    ?>

    <?= \diecoding\pdfjs\PdfJs::widget([
        'url' => $pdfUrl,
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