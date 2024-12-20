<?php

namespace app\views\library;

use app\models\library\PdfCardModel;
use yii\bootstrap5\Html;


// TODO why did i create it instead of using usual partial form?
class PdfCardGenerator
{
    // ops. id is not neccessary here because it's offline app.
    /** @param PdfCardModel $pdfCard */
    public static function render($pdfCard)
    {
        // TODO redo via ob
        $result = Html::beginTag('li', ['class' => 'pdf-file-card']);
        $result .= Html::a(Html::encode(str_replace('.pdf', '', $pdfCard->name)), '/stare-at/' . Html::encode($pdfCard->name) . '?page=' . Html::encode($pdfCard->bookmark), ['class' => 'pdf-file-link ajax-action']);
        $result .= " " . Html::encode($pdfCard->bookmark) . " p.";
        $result .= Html::endTag('li');
        return $result;
    }
}
