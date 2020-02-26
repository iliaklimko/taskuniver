<?php
use kartik\date\DatePicker;
// $dates = array_map(function ($dateString) {
//     return Yii::$app->formatter->asDate($dateString, 'php:d.m.Y H:i');
// }, $model->getNearestDates());
/*$dates = array_map(function ($dateString) {
    $nearestDate = trim($dateString);
    $nearestTime = substr($nearestDate, -5);
    return Yii::$app->formatter->asDate($nearestDate, 'php:j F Y')
            . ' '
            . Yii::t('app', 'ExcursionViewPage.atTime')
            . ' '
            . $nearestTime;
}, $model->getNearestDates());*/
use common\models\Excursion;

$oneTimeExcursion = $model->one_time_excursion;
$oneTimeExcursionCountPlaces = $model->visitors;

if ($oneTimeExcursion == 'Y') {
    $nearestDate = date('Y-m-d', strtotime($model->set_to));
    $newCount = $oneTimeExcursionCountPlaces;
} else {
    $arr = unserialize($model->date_array);
    $nextDay = array();

    foreach ($arr as $keyDay => $value) {
        if ($arr[$keyDay]['active'] == 'Y' && $arr[$keyDay]['count'] > 0) {
            $dateDay = date('Y-m-d', strtotime($keyDay));
            $nextDay[$dateDay] = $dateDay;
        }
    }

    if (empty($nextDay)) {

        $nearestDate = '2020-02-17';
        $newCount = 0;

    } else {

        $arrDateAndCount = Excursion::getDatesAndQuantity($model->id);

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
                        if(empty($nextDayNew)) {
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

        $time = new DateTime($nearestDate);
        $nameDay = $time->format('l');
    }
}
?>

<?php if (!empty($nearestDate)): ?>

    <div class="excursions__in-info__item">
        <div class="excursions__in-info__title">
            <?= Yii::t('app', 'ExcursionViewPage.datesAll') ?>:
        </div>
        <div class="excursions__in-info__right">
            <input id="datepickerTwo" type="hidden">
            <div class="error-wrap date-wrap">
                <a  class="date-wrap__link_two" id="datepickerTwo"></a>
            </div>
        </div>

    </div>


<!-- <div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        Время начала:
    </div>
    <div class="excursions__in-info__right">
        <?php /*echo $model->start_time;*/ ?>
    </div>
</div> -->
<?php endif; ?>
