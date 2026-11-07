<?php

/** Flash-сообщения админки (success / error) */

use yii\helpers\Html;

foreach (['success', 'error'] as $type) {
    $message = Yii::$app->session->getFlash($type);
    if ($message) {
        echo Html::tag('div', Html::encode($message), [
            'class' => 'admin-alert admin-alert--' . $type,
            'role' => $type === 'error' ? 'alert' : 'status',
        ]);
    }
}
