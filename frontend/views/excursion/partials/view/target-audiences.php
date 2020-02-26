<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.suitableForPeople') ?>:
    </div>
    <div class="excursions__in-info__right excursions__in-info__right--cathegory">
        <?php foreach ($model->targetAudiences as $audience): ?>
        <div class="people-cathegory">
            <i>
                <img src="/static/excursion/dist/<?= $audience->getPeopleCategoryUrl() ?>" alt="">
            </i>
            <span><?= $audience->name ?></span>
        </div>
        <?php endforeach;?>
    </div>
</div>
