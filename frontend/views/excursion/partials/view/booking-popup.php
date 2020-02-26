<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use frontend\assets\BookingPopupAsset;
use common\models\User as User;
use common\models\Excursion;

$this->registerJs(
    'var paymentResultUrl ="'
    . Url::to(['payment/result'])
    . '";',
    yii\web\View::POS_HEAD
);

$paid = $excursion->current_price * 100 > 0;

$paidPercent = 0;
$Card = 0;
$Cash = 0;

if ($paid) {
    $obUser = User::findIdentity($excursion->user_id);
    if (!empty($obUser->pay_percent))
        $paidPercent = $obUser->pay_percent;
    if (!empty($obUser->can_paid_by_card))
        $Card = $obUser->can_paid_by_card;
    if (!empty($obUser->pay_cash))
        $Cash = $obUser->pay_cash;
}

$oneTimeExcursion = $excursion->one_time_excursion;
$oneTimeExcursionCountPlaces = $excursion->visitors;
$arrDateAndCount = Excursion::getDatesAndQuantity($excursion->id);

if ($oneTimeExcursion == 'Y') {
    $nearestDate = date('Y-m-d', strtotime($excursion->set_to));
    $newCount = $oneTimeExcursionCountPlaces;
    $arrAllDaysNew[] = $excursion->set_to;

    if (!empty($arrDateAndCount)) {

        if (array_key_exists($excursion->set_to, $arrDateAndCount)) {

            $count = $oneTimeExcursionCountPlaces;
            $newCount = $count - array_sum($arrDateAndCount[$excursion->set_to]);

        } else {
            $newCount = $count;
        }
    } else {
        $newCount = $oneTimeExcursionCountPlaces;
    }

} else {
    $arr = unserialize($excursion->date_array);

    foreach ($arr as $keyDay => $value) {
        if ($arr[$keyDay]['active'] == 'Y' && $arr[$keyDay]['count'] > 0) {
            $dateDay = date('Y-m-d', strtotime($keyDay));
            $nextDay[$dateDay] = $dateDay;
        }
    }

    if (empty($nextDay)) {
        $arrAllDaysNew[] = '';
        $newCount = 0;

    } else {

        $set_to = $excursion->set_to;

        $arrDaysWeekNew = array();


        $endDate = strtotime($set_to);
        $arrAllDays = array();

        foreach ($arr as $key => $day) {

            if ($arr[$key]['active'] == 'Y' && !empty($arr[$key]['count'])) {
                for ($i = strtotime($key, strtotime(date('Y-m-d', strtotime($key)))); $i <= $endDate; $i = strtotime('+1 week', $i)) {
                    $arrAllDays[] = date('Y-m-d', $i);
                }
            } else {
                $arrAllDays[] = '';
            }
        }


        foreach ($arrAllDays as $oneDay => $valueOneDay) {
            if (!empty($valueOneDay)) {
                $time = new DateTime($valueOneDay);
                $nameDay = $time->format('l');
                $count = $arr[mb_strtolower($nameDay)]['count'];

                if (array_key_exists($valueOneDay, $arrDateAndCount)) {
                    $newCount = $count - array_sum($arrDateAndCount[$valueOneDay]);

                    if ($newCount <= 0) {
                        $arrAllDays[$oneDay] = '';
                    }
                } else {
                    $newCount = $count;
                }

                $arrAllDaysNew = $arrAllDays;

            }
        }

    }
}

BookingPopupAsset::register($this);
?>
<div id="booking" class="dnone">
    <div class="popup popup--entry">
        <div class="popup__title popup__title--centered"><?= Yii::t('app', 'BookingPopup.title') ?></div>
        <div class="popup__excursion-name">
            «<?= $excursion->title ?>»
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'booking-form',
            'options' => ['class' => 'popup__form'],
            'errorCssClass' => 'input-wrap--error',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"error-wrap\">{input}\n{error}</div>",
                'options' => ['class' => 'input-wrap'],
                'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
            ],
        ]); ?>
            <input type="hidden" id="currency-booking-form"        name="<?= $bookingForm->formName() ?>[currency]"        value="<?= Yii::$app->currency ?>">
            <input type="hidden" id="price-booking-form"           name="<?= $bookingForm->formName() ?>[price]"           value="<?= round(Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price), 2) ?>">
            <?= Html::activeHiddenInput($bookingForm, 'dates_available', ['id' => 'dates_available-booking-form']) ?>
            <?= $form->field($bookingForm, 'date', [
                'template' => "{label}\n<div class=\"error-wrap date-wrap\">{input}\n<a href=\"#\" class=\"date-wrap__link\"></a>\n{hint}\n{error}</div>",
            ])->textInput([
                'placeholder' => Yii::t('app', 'BookingForm.datePlaceholder'),
                'id' => 'datepicker',
                'arrAllDays' => $arrAllDaysNew,
                'id-excursion' => $excursion->id,
                'readonly' => true,
            ])->label(Yii::t('app', 'BookingForm.date'))
            ->hint('<div class="input-wrap__overflow-msg date-hint">' . Yii::t('app', 'BookingPopup.dateHint') . '</div>') ?>
            <?= $form->field($bookingForm, 'name')->textInput()->label(Yii::t('app', 'BookingForm.name').' <sup>*</sup>') ?>
            <?= $form->field($bookingForm, 'phone')->textInput()->label(Yii::t('app', 'BookingForm.phone').' <sup>*</sup>') ?>
            <?= $form->field($bookingForm, 'email')->textInput()->label(Yii::t('app', 'BookingForm.email').' <sup>*</sup>') ?>

        <div class="input-wrap">
                <label><?= Yii::t('app', 'BookingForm.count') ?>:</label>
                <div class="error-wrap">
                    <select class="fs"
                            id="count-booking-form"
                            name="<?= $bookingForm->formName() ?>[count]"
                    >
                        <? if (count(array_filter($arrAllDaysNew)) == 1) {
                            if ($newCount > 10) {
                                $countOption = 10;
                            } else {
                                $countOption = $newCount;
                            }
                            for ($i = 1; $i <= $countOption; $i++) {
                                ?>
                                <option><?= $i ?></option>
                            <? } ?>

                        <? } ?>

                    </select>
                </div>
            </div>
            <div class="popup__excursion-total <?= !$paid ? 'padding-left-204' : null ?>">
                <div class="popup__excursion-sum">
                    <?php if ($excursion->current_price * 100 > 0): ?>
                        <?= Yii::t('app', 'BookingPopup.price') ?>
                        <span id="total-booking-form">
                                <?= Yii::$app->formatter->asDecimal(
                                Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price),
                                2
                            ) ?>
                        </span>
                        <span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                    <?php endif; ?>
                </div>
                <?php if ($paid) : ?>
                <div class="input-wrap">
                    <div class="input-wrap__overflow">
                        <div class="checkbox-wrap">
                            <input type="checkbox" id="agreement" name="agreement">
                            <label for="agreement">
                                <?= Yii::t('app', 'BookingForm.agreement {link}', [
                                    'link' => Url::to([
                                                'static-page/view',
                                                'page_alias' => 'payment-rules',
                                                'locale' => Yii::$app->language !== 'ru' ? Yii::$app->language : null,
                                                'target_audience' => Yii::$app->request->get('target_audience')
                                            ])
                                ]) ?>
                            </label>
                            <span id="agreement-error" class="label-error dnone"><?= Yii::t('app', 'BookingForm.agreeHint') ?></span>
                        </div>
                    </div>
                </div>

                <? if (!empty($paidPercent)) { ?>
                    <div class="input-wrap payment-block">
                        <div class="input-wrap__overflow">
                            <div class="radio-wrap">
                                <input type="radio" id="full_payment" name="prepayment_percent" checked="checked" value="0">
                                <label for="full_payment">
                                    <?= Yii::t('app', 'BookingForm.payment', [
                                        'percent' => 100,
                                        'price' => Yii::$app->formatter->asDecimal(
                                            Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price), 2),
                                        'currency' => '<span class="' . (Yii::$app->currency == 'RUB' ? 'rouble' : 'euro') .'">' . (Yii::$app->currency == 'RUB' ? '₽' : '&euro;') . '</span>'
                                    ]) ?><p>&nbsp;</p><p>&nbsp;</p>
                                </label>
                            </div>
                            <div class="radio-wrap">
                                <input type="radio" id="percent_payment" name="prepayment_percent" value="<?= $paidPercent?>">
                                <label for="percent_payment">
                                    <?= Yii::t('app', 'BookingForm.payment', [
                                        'percent' => $paidPercent,
                                        'price' => Yii::$app->formatter->asDecimal(
                                            Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price * ($paidPercent / 100)), 2),
                                        'currency' => '<span class="' . (Yii::$app->currency == 'RUB' ? 'rouble' : 'euro') .'">' . (Yii::$app->currency == 'RUB' ? '₽' : '&euro;') . '</span>'
                                    ]) ?>
                                    <?
                                    if(!empty($Card) && !empty($Cash)) {
                                        echo Yii::t('app', 'BookingForm.paymentMethodCashAndCard');
                                    } elseif (!empty($Card)) {
                                        echo Yii::t('app', 'BookingForm.paymentMethodCard');
                                    } elseif (!empty($Cash)) {
                                        echo Yii::t('app', 'BookingForm.paymentMethodCash');
                                    } else {
                                        echo Yii::t('app', 'BookingForm.paymentMethod');
                                    }
                                    ?>
                                    <?= Yii::t('app', 'BookingForm.paymentFor', [
                                        'price' => Yii::$app->formatter->asDecimal(
                                            Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price - ($excursion->current_price * ($paidPercent / 100))), 2),
                                        'currency' => '<span class="' . (Yii::$app->currency == 'RUB' ? 'rouble' : 'euro') .'">' . (Yii::$app->currency == 'RUB' ? '₽' : '&euro;') . '</span>'
                                    ]) ?>
                                </label>
                            </div>
                        </div>
                    </div>
                <? } ?>

                <div class="popup__excursion-payment">
                    <!-- <label>&nbsp;</label> -->
                    <input class="btn" type="submit" value="<?= Yii::t('app', 'BookingForm.payButton') ?>">
                    <!-- <a href="#" class="btn"><?= Yii::t('app', 'BookingForm.payButton') ?></a> -->
                    <span class="popup__excursion-redirect">
                        <?= Yii::t('app', 'BookingForm.payButtonHint {button}', ['button' => Yii::t('app', 'BookingForm.payButton')]) ?>
                    </span>
                </div>
                <?php else: ?>
                <div class="popup__excursion-payment">
                    <!-- <label>&nbsp;</label> -->
                    <input class="btn" type="submit" value="<?= Yii::t('app', 'BookingForm.freeBook') . ' ' . Yii::t('app', 'FreePriceLC') ?>">
                </div>
                <?php endif; ?>
            </div>
            <?php if ($paid) : ?>
            <div class="cards">
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card1.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card2.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card3.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card4.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card5.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card6.png" alt="">
                </div>
                <div class="cards__item">
                    <img src="/frontend/web/css/dist/img/content/card7.png" alt="">
                </div>
            </div>
            <?php endif; ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>
