<?php

namespace yii\helpers;

use Yii;

class UserUploadsPathMaker {
    public static function getUserUploadsPath(): string {
        return Yii::getAlias('@uploads') . "/" . Yii::$app->user->identity->name;
    }

    public static function toFile(string $pdfName, bool $uploadsAsRoot = false): string {
        $fileName = "$pdfName.pdf";
        return $uploadsAsRoot
            ? "uploads/" . Yii::$app->user->identity->name . "/$fileName"
            : self::getUserUploadsPath() . "/$fileName";
    }
}