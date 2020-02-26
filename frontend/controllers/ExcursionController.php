<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use frontend\models\Excursion;
use common\models\ExcursionType;
use common\models\ExcursionTheme;
use common\models\ExcursionGroups;
use common\models\Language;
use common\models\City;
use frontend\models\BookingForm;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\models\Order;
use DateTime;

class ExcursionController extends BaseController
{
    const DEFAULT_ORDER_BY = 'current_price-asc';

    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }
        if ($action->id == 'view') {
            $this->layout = 'excursion-view';
        }
        if ($action->id == 'index') {
            $this->layout = 'excursion-index';
        }
        return true;
    }

    public function actionIndex(
        $target_audience = null,
        $order_by = self::DEFAULT_ORDER_BY,
        $type = null,
        $theme = null,
        $language = null,
        $duration = null,
        $start_city = null,
        $price_status = null,
        $person_number = null,
        $group_code = null,
        $dates = null
    )
    {

        if ($_target_audience = Yii::$app->request->get('_target_audience')) {
            if (empty($_target_audience) || $_target_audience == 'all') {
                $target_audience = null;
            } else {
                $target_audience = $_target_audience;
            }
            return $this->redirect([
                'index',
                'locale' => Yii::$app->language !== 'ru' ? Yii::$app->language : null,
                'target_audience' => $target_audience,
                'order_by' => $order_by,
                'type' => $type,
                'theme' => $theme,
                'language' => $language,
                'duration' => $duration,
                'start_city' => $start_city,
                'price_status' => $price_status,
                'person_number' => $person_number,
                'dates' => $dates,
                'group_code' => $group_code,
            ]);
        }
        return $this->render('index', array_merge(
            $this->getExcursionListWithPagination(
                $target_audience,
                $order_by,
                $type,
                $theme,
                $language,
                $duration,
                $start_city,
                $price_status,
                $person_number,
                $dates,
                $group_code
            ),
            [
                'excursionTypeList' => $this->getExcursionTypeList(),
                'excursionThemeList' => $this->getExcursionThemeList(),
                'languageList' => $this->getLanguageList(),
                'cityList' => $this->getCityList(),
                'excursionGroups' => $this->getExcursionGroups($group_code),
            ]
        ));
    }

    public function actionCountPlacesExcursion()
    {
        $arrExcursion = Excursion::getCountExcursion($_POST['idExcursion']);
        $arrDateAndCount = Excursion::getDatesAndQuantity($_POST['idExcursion']);
        $valOneTimeExcursion = $_POST['valOneTimeExcursion'];
        if ($valOneTimeExcursion == 'Y') {
            $count = $arrExcursion->visitors;

            if (!empty($arrDateAndCount)) {

                if (array_key_exists($_POST['date'], $arrDateAndCount)) {

                    $count = $arrExcursion->visitors;
                    $newCount = $count - array_sum($arrDateAndCount[$_POST['date']]);

                } else {
                    $newCount = $count;
                }
            } else {
                $newCount = $count;
            }
        } else {

            $arr = unserialize($arrExcursion->date_array);

            $time = new DateTime($_POST['date']);
            $nameDay = $time->format('l');
            $count = $arr[mb_strtolower($nameDay)]['count'];

            if (!empty($arrDateAndCount)) {

                if (array_key_exists($_POST['date'], $arrDateAndCount)) {

                    $time = new DateTime($_POST['date']);
                    $nameDay = $time->format('l');
                    $count = $arr[mb_strtolower($nameDay)]['count'];
                    $newCount = $count - array_sum($arrDateAndCount[$_POST['date']]);

                } else {
                    $newCount = $count;
                }
            } else {
                $newCount = $count;
            }
        }

        echo $newCount;
    }


    public function actionView($excursion_id, $target_audience = null)
    {
        if (!empty($_POST['prepayment_percent'])) {
            $_POST['BookingForm']['prepayment_percent'] = $_POST['prepayment_percent'];
            $_POST['BookingForm']['prepayment'] = ($_POST['BookingForm']['price'] * ((int)$_POST['prepayment_percent'] / 100)) * 100;
        } else {
            $_POST['BookingForm']['prepayment_percent'] = 0;
            $_POST['BookingForm']['prepayment'] = 0;
        }

        $request = Yii::$app->request;
        $excursion = $this->findExcursion($excursion_id);
        $dataProvider = $this->findSimilarExcursionsToCurrent($excursion);
        $bookingForm = new BookingForm($excursion);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($bookingForm->load($request->post()) && $bookingForm->validate()) {
                $order = $bookingForm->createOrder();
                if ($order->price * 100 > 0) {
                    try {
                        $result = Yii::$app->payler->startPay($order);
                    } catch (\RuntimeException $e) {
                        $result = [
                            'response' => [
                                'error' => <<<MSG
Не удалось создать сессию и провести оплату. Возможные причины:
Используется неправильный платежный ключ
При создании сессии используется неуникальный номер заказа
MSG
                            ]
                        ];
                    }
                } else {
                    return [
                        'response' => [
                            'order_id' => $order->code,
                        ]
                    ];
                }
                return $result;
            }
            // else
        }
        return $this->render('view', [
            'excursion' => $excursion,
            'dataProvider' => $dataProvider,
            'bookingForm' => $bookingForm,
            'order' => $this->findOrder($request->get('order_id')),
            'excursionGroups' => $this->getExcursionGroups($request->get('group_code')),
        ]);
    }

    protected function findOrder($code)
    {
        if (!$code) {
            return null;
        }
        return Order::findOne(['code' => $code]);
    }

    protected function findSimilarExcursionsToCurrent(Excursion $excursion)
    {
        return new ActiveDataProvider([
            'query' => $excursion->getSimilarExcursions(Excursion::STATUS_ACCEPTED),
            'pagination' => [
                'pageSize' => 4,
            ],
        ]);
    }

    protected function findExcursion($id)
    {
        $query = Excursion::find()
            ->joinWith('translations')
            ->andWhere(['{{%excursion_translation}}.language_code' => Yii::$app->language])
            ->andWhere(['{{%excursion}}.id' => $id]);
        if (Yii::$app->request->get('mode') !== 'force') {
            $query->andWhere(['status' => Excursion::STATUS_ACCEPTED]);
        }
        $excursion = $query->one();
        if (!$excursion) {
            throw new NotFoundHttpException();
        }
        return $excursion;
    }

    protected function getExcursionListWithPagination(
        $target_audience,
        $order_by,
        $type,
        $theme,
        $language,
        $duration,
        $start_city,
        $price_status,
        $person_number,
        $dates,
        $group_code,
        $page_size = 15
    )
    {
        $query = $this->getFilterQuery(
            $target_audience,
            $order_by,
            $type,
            $theme,
            $language,
            $duration,
            $start_city,
            $price_status,
            $person_number,
            $dates,
            $group_code
        );
        $pagination = new Pagination([
            'totalCount' => $query->count(),
            'defaultPageSize' => $page_size,
        ]);
        $excursionList = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return [
            'excursionList' => $excursionList,
            'pagination' => $pagination,
        ];
    }

    protected function getFilterQuery(
        $target_audience,
        $order_by,
        $type,
        $theme,
        $language,
        $duration,
        $start_city,
        $price_status,
        $person_number,
        $dates,
        $group_code
    )
    {
        $order = str_replace('-', ' ', $order_by);
        $query = Excursion::find()
            ->select(['{{%excursion}}.*, {{%excursion_groups}}.code as group_code', 'IF({{%excursion}}.current_price > 0, 1, 0) AS excursion_price_status'])
            ->joinWith(['translations', 'types', 'themes', 'languages', 'startCity'])
            ->with(['translations', 'types.translations', 'themes.translations', 'languages.translations', 'startCity.translations'])
            ->andWhere(['status' => Excursion::STATUS_ACCEPTED])
            ->andWhere(['{{%excursion_translation}}.language_code' => Yii::$app->language])
            ->andWhere(['not', ['user_id' => null]])
            ->andFilterWhere(['{{%excursion__excursion_type}}.excursion_type_id' => $type])
            ->andFilterWhere(['{{%excursion__excursion_theme}}.excursion_theme_id' => $theme])
            ->andFilterWhere(['{{%excursion__language}}.language_id' => $language])
            ->andFilterWhere(['{{%excursion}}.duration' => $duration])
            ->andFilterWhere(['{{%excursion}}.person_number' => $person_number])
            ->andFilterWhere(['{{%excursion}}.start_city_id' => $start_city])
            ->orderBy($order)
            ->groupBy('{{%excursion}}.id');
        if ($target_audience) {
            $query->joinWith('targetAudiences')
                ->andWhere(['{{%target_audience}}.url_alias' => $target_audience]);
        }
        if ($price_status != '') {
            if ($price_status == 1) {
                $query->andFilterWhere(['>', '{{%excursion}}.current_price', 0]);
            }
            if ($price_status == 0) {
                $query->andFilterWhere(['not', ['>', '{{%excursion}}.current_price', 0]]);
            }
        }
        if (empty($dates)) {
            $query->andWhere('dates > :date OR dates = ""', ['date' => date('Y-m-d')]);
        } else {
            $query->andFilterWhere(['between', '{{%excursion}}.dates', $dates . ' 00:00', $dates . ' 23:59']);
        }
        if ($group_code) {
            $obGroups = ExcursionGroups::find()->select('id')->where(array('code' => $group_code))->one();
            if (!empty($obGroups->id))
                $query->andWhere(array('group_id' => $obGroups->id));
            else
                throw new NotFoundHttpException();

        }
        $query->joinWith(['excursion_groups']);

        return $query;
    }

    protected function getExcursionTypeList()
    {
        return ExcursionType::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionThemeList()
    {
        return ExcursionTheme::find()
            ->with('translations')
            ->all();
    }

    protected function getLanguageList()
    {
        return Language::find()
            ->with('translations')
            ->all();
    }

    protected function getCityList()
    {
        return City::find()
            ->with('translations')
            ->all();
    }

    protected function getExcursionGroups($cur)
    {
        $subQuery = (new \yii\db\Query())
            ->select(['group_id'])
            ->from('excursion')
            ->groupBy('group_id')
            ->where('dates > :date OR dates = ""', ['date' => date('Y-m-d')]);
        $result['ob'] = ExcursionGroups::find()
            ->with('translations')
            ->andWhere(['in','id', $subQuery])
            ->orderBy(['priority' => SORT_ASC])
            ->all();
        $result['current'] = $cur;
        $result['title'] = '';

        if (!empty($cur) && !empty($result['ob'])) {
            foreach ($result['ob'] as $ob) {
                if ($ob->code == $cur) {
                    $result['title'] = $ob->name;
                    break;
                }
            }
        }

        return $result;
    }

    public function actionValidateFilter()
    {
        $request = Yii::$app->request;
        $target_audience = $request->post('_target_audience');
        $target_audience = $target_audience == 'all' ? null : $target_audience;
        $order_by = $request->post('order_by');
        $type = $request->post('type');
        $theme = $request->post('theme');
        $language = $request->post('language');
        $duration = $request->post('duration');
        $start_city = $request->post('start_city');
        $price_status = $request->post('price_status');
        $person_number = $request->post('person_number');
        $dates         = $request->post('dates');
        $query = $this->getFilterQuery(
            $target_audience,
            $order_by,
            $type,
            $theme,
            $language,
            $duration,
            $start_city,
            $price_status,
            $person_number,
            $dates,
            null
        );
        Yii::$app->response->format = Response::FORMAT_JSON;
        $count = $query->count();
        $message = Yii::t('app', 'LeftFilterTooltip.notFound');
        if ($count > 0) {
            $message = Yii::t('app', 'Found {cnt} excursions', ['cnt' => $count]);
        }
        return [
            'count' => $count,
            'message' => $message,
        ];
    }
}
