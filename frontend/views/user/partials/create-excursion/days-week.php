<?php

use yii\helpers\Html;
use common\models\Excursion;
use kartik\date\DatePicker;
if($model->date_array === 'Y') {
    $arr = NULL;
} else {
    $arr = unserialize($model->date_array);
}
$oneTimeExcursion = $model->one_time_excursion;
$arrError = $model->errors;
?>
<div class="input-wrap">
    <label><?= Yii::t('app', 'ExcursionViewPage.days') ?><sup>*</sup></label>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="mondayValue" type="hidden" value="N" name="Excursion[mondayDays]">
            <input type="checkbox"
                   id="monday"
                   name="Excursion[mondayDays]"
                   value="<?= ($arr['monday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   class="days"
                <?= ($arr['monday']['active'] == 'Y') ? 'checked' : null  ?>

            >
            <label for="monday"><?= Yii::t('app','ExcursionViewPage.monday') ?></label>
        </div>
    </div>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="fridayValue" type="hidden"  value="N" name="Excursion[fridayDays]">
            <input type="checkbox"
                   id="friday"
                   class="days"
                   value="<?= ($arr['friday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   name="Excursion[fridayDays]"
                <?= ($arr['friday']['active'] == 'Y') ? 'checked' : null  ?>

            >
            <label for="friday"><?= Yii::t('app','ExcursionViewPage.friday') ?></label>
        </div>
    </div>
</div>

<div class="input-wrap">
    <label></label>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input  id="tuesdayValue" type="hidden"  value="N" name="Excursion[tuesdayDays]">
            <input type="checkbox"
                   id="tuesday"
                   value="<?= ($arr['tuesday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   class="days"
                   name="Excursion[tuesdayDays]"
                <?= ($arr['tuesday']['active'] == 'Y') ? 'checked' : null  ?>

            >
            <label for="tuesday"><?= Yii::t('app','ExcursionViewPage.tuesday') ?></label>
        </div>
    </div>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="saturdayValue" type="hidden"  value="N" name="Excursion[saturdayDays]">
            <input type="checkbox"
                   id="saturday"
                   class="days"
                   value="<?= ($arr['saturday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   name="Excursion[saturdayDays]"
                <?= ($arr['saturday']['active'] == 'Y') ? 'checked' : null  ?>

            >
            <label for="saturday"><?= Yii::t('app','ExcursionViewPage.saturday') ?></label>
        </div>
    </div>
</div>

<div class="input-wrap">
    <label></label>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="wednesdayValue" type="hidden" value="N" name="Excursion[wednesdayDays]">
            <input type="checkbox"
                   id="wednesday"
                   class="days"
                   value="<?= ($arr['wednesday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   name="Excursion[wednesdayDays]"
                <?= ($arr['wednesday']['active'] == 'Y') ? 'checked' : null  ?>

            >
            <label for="wednesday"><?= Yii::t('app','ExcursionViewPage.wednesday') ?></label>
        </div>
    </div>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="sundayValue" type="hidden"  value="N" name="Excursion[sundayDays]">
            <input type="checkbox"
                   id="sunday"
                   class="days"
                   value="<?= ($arr['sunday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   name="Excursion[sundayDays]"
                <?= ($arr['sunday']['active'] == 'Y') ? 'checked' : null  ?>
            >
            <label for="sunday"><?= Yii::t('app','ExcursionViewPage.sunday') ?></label>
        </div>
    </div>
</div>

<div class="input-wrap">
    <label></label>
    <div class="input-wrap__overflow">
        <div class="checkbox-wrap">
            <input id="thursdayValue" type="hidden"  value="N" name="Excursion[thursdayDays]">
            <input type="checkbox"
                   id="thursday"
                   class="days"
                   value="<?= ($arr['thursday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                   name="Excursion[thursdayDay]"
                <?= ($arr['thursday']['active'] == 'Y') ? 'checked' : null  ?>
            >
            <label for="thursday"><?= Yii::t('app','ExcursionViewPage.thursday') ?></label>

        </div>
        <span class="label-error"><?= (!empty($arrError['date_array'][0])) ? $arrError["date_array"][0] : ''  ?></span>
    </div>
        <div class="input-wrap__overflow">
            <div class="checkbox-wrap">
                <input type="hidden" name="<?= $model->formName() ?>[one_time_excursion]" value="N">
                <input type="checkbox"
                       id="one_time_excursion"
                       name="<?= $model->formName() ?>[one_time_excursion]"
                       value="<?= ($oneTimeExcursion == 'Y') ? 'Y' : 'N'  ?>"
                    <?= ($oneTimeExcursion == 'Y')? 'checked' : null ?>
                >
                <label for="one_time_excursion"><?= Yii::t('app', 'ExcursionViewPage.oneTimeExcursion' ) ?></label>
            </div>
        </div>
</div>



