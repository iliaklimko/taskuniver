<?php

use yii\helpers\Html;
use common\models\Excursion;
use kartik\date\DatePicker;

if($model->date_array === 'Y') {
    $arr = NULL;
} else {
    $arr = unserialize($model->date_array);
}
?>

<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.monday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['monday']['count'])) ? $arr['monday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[monday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.tuesday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['tuesday']['count'])) ? $arr['tuesday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[tuesday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.wednesday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['wednesday']['count'])) ? $arr['wednesday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[wednesday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.thursday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['thursday']['count'])) ? $arr['thursday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[thursday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.friday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['friday']['count'])) ? $arr['friday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[friday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.saturday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['saturday']['count'])) ? $arr['saturday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[saturday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
<div class="input-wrap field-excursion-days_count daysCount">
    <label class="control-label" for="excursion-free_cancellation"><?=Yii::t('app', 'ExcursionViewPage.sunday'); ?>:</label>
    <div class="input-wrap__overflow"><input value="<?= (!empty($arr['sunday']['count'])) ? $arr['sunday']['count'] : 0 ?>" type="text" id="excursion-free_cancellation" class="price" name="Excursion[sunday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
        <div class="input-wrap__overflow-msg"><?=Yii::t('app', 'ExcursionViewPage.numberOfSeats'); ?></div>
        <span class="label-error"></span></div>
</div>
