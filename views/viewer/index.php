<?php

/** @var yii\web\View $this */
/** @param PdfModel $pdfModel  */

use app\models\viewer\PdfModel;
use diecoding\pdfjs\PdfJs;
use yii\bootstrap5\Html;
use yii\helpers\Url;
use yii\helpers\UserUploadsPathHelper;

?>
<div class="pdf-viewer-container">
    <?php if ($pdfModel == null): ?>
        <h3>
            You don't have any <strong>PDF</strong> in your
            <?= Html::a('library', Url::to('/library'), ['class' => 'ajax-action']); ?>
            .
            <?php // TODO add pdf uloading form right here for convenience ?>
            <select>
                <option>Or you are advanced user</option>
                <option>who doesn't use cookies.</option>
                <option>You know, here could be your</option>
                <option>last opened pdf, which makes your life simpler,</option>
                <option>allowing you to not remember your last opening pdf. </option>
                <option>But you picked a hard way.</option>
                <option>Well, deal with it by your own.</option>
                <option>Or just enable cookies the next time.</option>
            </select>
        </h3>
    <?php else: ?>
        <nav id="custom-toolbar">
            <ul class="navbar-nav nav">
                <li id="update-bookmark-container">
                    <?= $this->render(Yii::getAlias('@partial_new_bookmark_form'), ['pdfId' => $pdfModel->id]) ?>
                </li>
            </ul>
        </nav>
        <?php $pdfUrl = $pdfModel->getPdfSpecified() ? Url::to([UserUploadsPathHelper::toFile($pdfModel->name, true), '#' => "page=$pdfModel->bookmark"]) : ''; ?>
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
    <?php endif ?>
</div>