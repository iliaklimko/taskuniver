<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\components\helpers\BaseHelper;
use common\models\Excursion;
use frontend\assets\NprogressAsset;
use common\models\User as User;

NprogressAsset::register($this);

$nearestDate = $excursion->getNearestDate();

$title = $excursion->title;
$h1 = $title;
$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ExcursionViewPage.breadcrumb'), 'url' => Url::to(BaseHelper::mergeWithCurrentParams(['excursion/index', 'excursion_id' => null, 'group_code' => null]))];

function getWord($number, $suffix) {
    $keys = array(2, 0, 1, 1, 1, 2);
    $mod = $number % 100;
    $suffix_key = ($mod > 7 && $mod < 20) ? 2: $keys[min($mod % 10, 5)];
    return $suffix[$suffix_key];
}

if (!empty($excursionGroups['title'])) {
    $this->params['breadcrumbs'][] = ['label' => $excursionGroups['title'], 'url' => Url::to(BaseHelper::mergeWithCurrentParams(['excursion/index', 'excursion_id' => null]))];
}
$this->params['breadcrumbs'][] = $h1;

$oneTimeExcursion = $excursion->one_time_excursion;
$oneTimeExcursionCountPlaces = $excursion->visitors;

$arrDateAndCount = Excursion::getDatesAndQuantity($excursion->id);

if($oneTimeExcursion == 'Y') {
    $nearestDate = date('Y-m-d', strtotime($excursion->set_to));
    $count = $oneTimeExcursionCountPlaces;

    if (!empty($arrDateAndCount)) {

        if (array_key_exists($excursion->set_to, $arrDateAndCount)) {

            $count = $oneTimeExcursionCountPlaces;
            $newCount = $count - array_sum($arrDateAndCount[$excursion->set_to]);

        } else {
            $newCount = $count;
        }
    } else {
        $newCount = $count;
    }

} else {
    $arr = unserialize($excursion->date_array);
    $nextDay = array();

    foreach ($arr as $keyDay => $value) {
        if ($arr[$keyDay]['active'] == 'Y' && $arr[$keyDay]['count'] > 0) {
            $dateDay = date('Y-m-d', strtotime($keyDay));
            $nextDay[$dateDay] = $dateDay;
        }
    }

    if(empty($nextDay)) {
        $nearestDate = '';
        $newCount = 0;

    } else {

        if (!empty($arrDateAndCount)) {
            foreach ($arrDateAndCount as $keyDate => $quantity) {
                if (array_key_exists($keyDate, $nextDay)) {

                    $time = new DateTime($keyDate);
                    $nameDay = $time->format('l');
                    $count = $arr[mb_strtolower($nameDay)]['count'];
                    $newCount = $count - array_sum($quantity);

                    if ($newCount <= 0) {
                        unset($nextDay[$keyDate]);
                        $nextDayNew = $nextDay;
                        if (empty($nextDayNew)) {
                            $newCount = 0;
                            $nearestDate = '';
                        } else {
                            $nearestDate = date('Y-m-d', strtotime(min($nextDayNew)));
                        }
                    } else {
                        $nearestDate = date('Y-m-d', strtotime(min($nextDay)));
                    }

                } else {
                    $nearestDate = date('Y-m-d', strtotime(min($nextDay)));
                }
            }
        } else {
            $nearestDate = date('Y-m-d', strtotime(min($nextDay)));
        }

        if (!empty($nearestDate)) {

            $time = new DateTime($nearestDate);
            $nameDay = $time->format('l');
            $count = $arr[mb_strtolower($nameDay)]['count'];
            if ($count <= 0) {
                unset($nextDay[$keyDate]);
                $nextDayNew = $nextDay;
                $nearestDate = date('Y-m-d', strtotime(min($nextDayNew)));
            }


            if (array_key_exists($nearestDate, $arrDateAndCount)) {
                $newCount = $count - array_sum($arrDateAndCount[$nearestDate]);
            } else {
                $newCount = $count;
            }
        }
    }

}

?>

<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($excursion->title) ?></h1>
        </div>
    </div>
    <!-- CONTENT-PART -->
    <div class="container-fluid">
        <div class="page-content__wrapper">
            <div class="author">
                <time class="author__date"
                    datetime="<?= Yii::$app->formatter->asDate($excursion->created_at, 'php:Y-m-d')?>"
                >
                    <?= Yii::t('app', 'ExcursionViewPage.added') ?>: <?= Yii::$app->formatter->asDate($excursion->created_at, 'php:j F Y') ?>
                </time>
                <div class="author__item">
                    <?= Yii::t('app', 'ExcursionViewPage.guide') ?>:
                    <?php if ($excursion->user->getUploadUrl('avatar')): ?>
                    <div class="author__img" style="background:url(<?= $excursion->user->getUploadUrl('avatar') ?>)"></div>
                    <?php endif; ?>
                    <a class="author__name"><?= $excursion->user->getFullName() ?></a>
                    <?php if (Yii::$app->user->isGuest): ?>
                    <a href="#"
                       class="excursion-favorite"
                       data-model-id="<?= $excursion->id ?>"
                    >
                        <i></i><span><?= Yii::t('app', 'Favorites.addToFavoritesButton') ?></span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php if ($excursion->user->id == Yii::$app->user->id): ?>
                <div class="author__item author__item_edit">
                <a href="<?= Url::to([
                    'user/update-excursion',
                    'locale' => $excursion->user->interface_language != 'ru' ? $excursion->user->interface_language : null,
                    'id' => $excursion->id,
                    'lang' => Yii::$app->language,
                ]) ?>" class="edit"><img src="/frontend/web/css/dist/img/svg/edit.svg" alt=""><span><?= Yii::t('app', 'ExcursionViewPage.editExcursion') ?></span></a>
                </div>
                <?php endif; ?>
            </div>
            <div class="excursions__in">
                <div class="excursions__in-img" style="background: url(<?= $excursion->getUploadUrl('featured_image') ?>)">
                    <div class="excursions__in-top">
                        <div class="container-min">
                            <div class="excursions__in-wrap">
                                <div class="excursions__in-item">

                                    <i>
                                        <img src="/frontend/web/css/dist/img/svg/flag.svg" alt="">
                                    </i>
                                    <? if($newCount <= 0) { ?>
                                            <span class="headDate" id-count="<?=$newCount?>"><?= Yii::t('app','BookingPopup.nearestExcursion')?></span>
                                    <? } else { ?>
                                    <span  class="headDate"  id="headDate" date="<?=$nearestDate ?>" id-oneTime="<?= ($oneTimeExcursion == 'Y') ? 'Y' : 'N' ?>" id-count="<?=$newCount?>">
                                        <?= Yii::$app->formatter->asDate($nearestDate, 'php:j F Y') ?>
                                        <?= Yii::t('app','BookingPopup.in')?>
                                        <?= date('H:i',strtotime($excursion->time_spending)) ?>

                                    </span>
                                    <? } ?>
                                </div>
                                <div class="excursions__in-item">
                                        <span class="headDate" id="<?=$newCount ?>" date="<?=$nearestDate ?>">
                                            <?
                                            $array = array(Yii::t('app','BookingPopup.aPlace'), Yii::t('app','BookingPopup.placesTwo'), Yii::t('app','BookingPopup.places'));
                                            $word = getWord($newCount, $array);
                                            echo "$newCount $word<br />";
                                            ?>
                                    </span>

                                </div>
                                <div class="excursions__in-item">
                                    <i>
                                        <img src="/frontend/web/css/dist/img/svg/geo.svg" alt="">
                                    </i>
                                    <span class="headDate"><?= $excursion->startCityName() ?></span>
                                </div>
                                <div class="excursions__in-item">
                                    <i>
                                        <img src="/frontend/web/css/dist/img/svg/clock_1.svg" alt="">
                                    </i>
                                    <span class="headDate"><?= Excursion::getDurationList()[$excursion->duration] ?></span>
                                </div>
                                <div class="excursions__in-item excursions__in-item--price">
                                    <div class="excursions__in-price <?= !($excursion->current_price * 100 > 0) ? 'top-10' : null ?>">
                                    <?php if ($excursion->current_price * 100 > 0): ?>
                                        <?= Yii::t('app', 'ExcursionViewPage.price') ?>: <?= Yii::$app->formatter->asDecimal(
                                                    Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->current_price),
                                                    2
                                              ) ?><span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                                        <div class="old-price">
                                            <?php if ($excursion->old_price * 100 > 0): ?>
                                            <?= Yii::$app->formatter->asDecimal(
                                                    Yii::$app->currencyConverter->convert($excursion->currency->code, $excursion->old_price),
                                                    2
                                            ) ?><span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                                            <?php endif; ?>
                                        </div>

                                    <?php else: ?>
                                        <?= Yii::t('app', 'FreePrice') ?>
                                    <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-min">
                    <div class="excursions__in-info">
                        <?= $this->render('partials/view/included', ['model' => $excursion]) ?>
                        <div class="excursions__in-left">
                            <?= $this->render('partials/view/target-audiences', ['model' => $excursion]) ?>
                            <?= $this->render('partials/view/dates', ['model' => $excursion]) ?>
                            <?= $this->render('partials/view/languages', ['model' => $excursion]) ?>
                            <div class="excursions__in-info__item">
                                <div class="excursions__in-info__title">
                                    <?= Yii::t('app', 'ExcursionViewPage.personNumber') ?>:
                                </div>
                                <div class="excursions__in-info__right">
                                    <?= $newCount ?>
                                </div>
                            </div>
                            <?= $this->render('partials/view/excursion-types', ['model' => $excursion]) ?>
                            <div class="excursions__in-info__item">
                                <div class="excursions__in-info__title">
                                    <?= Yii::t('app', 'ExcursionViewPage.meetingPoint') ?>:
                                </div>
                                <div class="excursions__in-info__right">
                                    <?= $excursion->meeting_point ?>
                                </div>
                            </div>
                            <?php if ($excursion->pick_up_from_hotel): ?>
                            <div class="excursions__in-info__item">
                                <div class="excursions__in-info__title">
                                    <?= Yii::t('app', 'ExcursionViewPage.transfer') ?>:
                                </div>
                                <div class="excursions__in-info__right">
                                    <?= Yii::t('app', 'ExcursionViewPage.pickupFromHotel') ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?= $this->render('partials/view/description', ['model' => $excursion]) ?>
                            <div class="excursions__in-info__item">
                                <div class="excursions__in-info__title">
                                    <?= Yii::t('app', 'ExcursionViewPage.additionalInfo') ?>:
                                </div>
                                <div class="excursions__in-info__right">
                                    <?= nl2br($excursion->additional_info) ?>
                                </div>
                            </div>
                            <?= $this->render('partials/view/excursion-themes', ['model' => $excursion]) ?>
                            <?= $this->render('partials/view/city-on-the-way', ['model' => $excursion]) ?>
                            <?= $this->render('partials/view/sights', ['model' => $excursion]) ?>
                            <?php if ($excursion->user->instant_confirmation): ?>
                            <div class="excursions__in-info__item">
                                <div class="excursions__in-info__title">
                                    <?= Yii::t('app', 'ExcursionViewPage.instantConfirmation') ?>:
                                </div>
                                <div class="excursions__in-info__right">
                                    <?= Yii::t('app', 'YesInstant') ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?= $this->render('partials/view/tags', ['model' => $excursion]) ?>
                        </div>
                    </div>
                    <div class="btn-wrap">
                        <?php if (Yii::$app->user->isGuest): ?>
                        <a href="#booking" class="btn btn--minimal fb-inline fb-inline--entry"><?= Yii::t('app', 'ExcursionViewPage.bookNowLong') ?></a>
                        <a href="#"
                           class="excursion-favorite"
                           data-model-id="<?= $excursion->id ?>"
                        >
                            <i></i><span><?= Yii::t('app', 'Favorites.addToFavoritesButton') ?></span>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="excursions__add">
        <div class="container-fluid">
            <div class="container-min">
                <div class="share">
                    <span><?= Yii::t('app', 'ExcursionViewPage.share') ?>:</span>
                    <?= $this->render('//post/partials/sharethis') ?>
                </div>
                <?php if (Yii::$app->user->isGuest): ?>
                <a href="#guide-enter" class="tags__offer fb-inline">
                    <i class="tags__offer-link"></i>
                    <span><?= Yii::t('app', 'ExcursionViewPage.addExcursion') ?></span>
                </a>
                <?php else: ?>
                <a href="<?= Url::to(['/user/create-excursion','locale' => Yii::$app->request->get('locale')]) ?>" class="tags__offer">
                    <i class="tags__offer-link"></i>
                    <span><?= Yii::t('app', 'ExcursionViewPage.addExcursion') ?></span>
                </a>
                <?php endif; ?>
                <?php if ($excursion->user->id == Yii::$app->user->id): ?>
                <a href="<?= Url::to([
                    'user/update-excursion',
                    'locale' => $excursion->user->interface_language != 'ru' ? $excursion->user->interface_language : null,
                    'id' => $excursion->id,
                    'lang' => Yii::$app->language,
                ]) ?>" class="excursion-edit"><span><?= Yii::t('app', 'ExcursionViewPage.editExcursion') ?></span></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if ($dataProvider->totalCount > 0): ?>
    <div class="excursions__bottom">
        <div class="container-fluid">
            <h2><?= Yii::t('app', 'ExcursionViewPage.similarExcursions') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'partials/excursion',
                'viewParams' => ['divClass' => 'col-xs-3'],
                'layout' => '{items}',
                'itemOptions' => [
                    'tag' => null,
                ],
                'options' => [
                    'class' => 'row',
                ],
            ]); ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<!--end CONTENT-PART -->

<?= $this->render('partials/view/booking-popup', [
    'bookingForm' => $bookingForm,
    'excursion'   => $excursion,
]) ?>
