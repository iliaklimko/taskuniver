<?php

use yii\helpers\Html;
use yii\helpers\Url;

$returnTo = Url::current();
?>

<?php /* ?>
<div class="page-header__currency">
    <?= Html::a(''
        . '<span class="currency-icon '
        . (Yii::$app->currency == 'RUB' ? null : 'rouble')
        . '">'
        . (Yii::$app->currency == 'RUB' ? '&euro;' : '₽')
        . '</span> '
        . (Yii::$app->currency == 'RUB' ? 'EUR' : 'RUB'),
        ['/currency-changer/run', 'returnTo' => $returnTo, 'currency' => Yii::$app->currency == 'RUB' ? 'EUR' : 'RUB'],
        [
            'data-method' => 'post',
            'data-pjax' => '0',
        ]
    ) ?>
</div>
<?php */ ?>

<div class="page-header__currency">
    <?php echo Html::beginForm(
        ['currency-changer/run'],
        'POST',
        ['id' => 'currency-changer-form']
    ); ?>
    <input type="hidden" name="returnTo" value="<?= $returnTo ?>" />
    <span class="currency-icon <?= Yii::$app->currency == 'RUB' ? 'rouble' : null ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
    <?php echo Html::dropDownList(
        'currency',
        Yii::$app->currency,
        array_combine(
            Yii::$app->currencySelector->supportedCurrencies,
            Yii::$app->currencySelector->supportedCurrencies
        ),
        ['class' => 'fs']
    ); ?>
    <?php echo Html::endForm(); ?>
</div>
