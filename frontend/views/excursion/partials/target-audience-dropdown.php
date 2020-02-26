<?php

use yii\helpers\Html;
use common\models\TargetAudience;

$activeTargetAudienceId = Yii::$app->request->get('target_audience');
$taList = $this->params['targetAudienceList'];
$taList = array_map(function ($item) use ($activeTargetAudienceId) {
    return [
        'value'    => empty($item->url_alias) ? 'all' : $item->url_alias,
        'name'     => $item->name,
        'selected' => $item->url_alias == $activeTargetAudienceId,
    ];
}, $taList);

$js = <<<JS
if ($('select[name="_target_audience"]').val() == 'all') {
    $('select[name="_target_audience"] + .jq-selectbox__select > .jq-selectbox__select-text').attr('style', 'color: #888');
}
$('select[name="_target_audience"]').change(function () {
    $('select[name="_target_audience"] + .jq-selectbox__select > .jq-selectbox__select-text').attr('style', 'color: #000');
});
JS;
$this->registerJs($js);
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.targetAudidence') ?></span></label>
    <div class="select-wrap">
        <select class="fs"
            name="_target_audience"
        >
            <?php foreach ($taList as $taItem): ?>
            <option value="<?= $taItem['value'] ?>"
                <?= $taItem['selected'] ? 'selected' : null ?>
            >
                <?= $taItem['name'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <span class="input-wrap__message"></span>
    </div>
</div>
