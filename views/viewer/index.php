<?php

/** @var yii\web\View $this */
/** @var $bookmark must not be null */

use \diecoding\pdfjs\PdfJs;

?>
<div class="pdf-viewer-container">
    <?php
    $pdfUrl = $pdfSpecified ? "uploads/" . Yii::$app->user->identity->name . "/$pdfName.pdf#page=$bookmark" : '';
    ?>

    <?= PdfJs::widget([
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
        'sections' => [
            'viewLayers' => false,
            'viewAttachments' => false,
        ]
    ]); ?>
</div>