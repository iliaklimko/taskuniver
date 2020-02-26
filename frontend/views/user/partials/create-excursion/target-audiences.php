<?php

use yii\helpers\Html;
use common\models\TargetAudience;

$wheelchair = $targetAudienceList[TargetAudience::ALIAS_WHEELCHAIR];
$hear = $targetAudienceList[TargetAudience::ALIAS_HEAR];
$see = $targetAudienceList[TargetAudience::ALIAS_SEE];
$mother = $targetAudienceList[TargetAudience::ALIAS_MOTHER];

$audienceIds = array_map(function ($audience) {
    return $audience->id;
}, $model->targetAudiences);
$audienceIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['targetAudienceIds'] ?: [])
    : $audienceIds;
?>

<div class="input-wrap <?= $model->hasErrors('targetAudienceIds') ? 'input-wrap--error' : null ?>">
    <label><?= Yii::t('app', 'UpdateExcursionPage.guestCategory') ?>: <sup>*</sup></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[targetAudienceIds]" value="">
        <div class="guest-category">
            <div class="checkbox-wrap cat1">
                <input type="checkbox"
                    id="guest-cetegory1"
                    name="<?= $model->formName() ?>[targetAudienceIds][]"
                    value="<?= $wheelchair->id ?>"
                    <?= in_array($wheelchair->id, $audienceIds) ? 'checked' : null ?>
                >
                <label for="guest-cetegory1"><?= $wheelchair->name ?></label>
            </div>
            <div class="checkbox-wrap cat2">
                <input type="checkbox"
                    id="guest-cetegory2"
                    name="<?= $model->formName() ?>[targetAudienceIds][]"
                    value="<?= $hear->id ?>"
                    <?= in_array($hear->id, $audienceIds) ? 'checked' : null ?>
                >
                <label for="guest-cetegory2"><?= $hear->name ?></label>
            </div>
            <div class="checkbox-wrap cat3">
                <input type="checkbox"
                    id="guest-cetegory3"
                    name="<?= $model->formName() ?>[targetAudienceIds][]"
                    value="<?= $see->id ?>"
                    <?= in_array($see->id, $audienceIds) ? 'checked' : null ?>
                >
                <label for="guest-cetegory3" class="cat3"><?= $see->name ?></label>
            </div>
            <div class="checkbox-wrap cat4">
                <input type="checkbox"
                    id="guest-cetegory4"
                    name="<?= $model->formName() ?>[targetAudienceIds][]"
                    value="<?= $mother->id ?>"
                    <?= in_array($mother->id, $audienceIds) ? 'checked' : null ?>
                >
                <label for="guest-cetegory4" class="cat4"><?= $mother->name ?></label>
            </div>
        </div>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.guestCategoryHint') ?>
        </div>
        <?= Html::error($model, 'targetAudienceIds', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
