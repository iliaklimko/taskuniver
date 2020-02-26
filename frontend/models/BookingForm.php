<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Order;

class BookingForm extends Model
{
    public $date;
    public $name;
    public $phone;
    public $email;

    public $currency;
    public $price;
    public $count;

    public $prepayment;
    public $prepayment_percent;

    public $dates_available;

    private $_excursion;

    public function rules()
    {
        return [
            [['name', 'phone', 'email'], 'trim'],
            [['name', 'phone', 'email'/*, 'date'*/], 'required'],
            [['name', 'phone'], 'string', 'min' => 2],
            ['date', 'match', 'pattern' => '/^\d{4}\-\d{2}\-\d{2}$/'],
            ['phone', 'match', 'pattern' => '/^\+[-\d\s]*\d$/', 'message' => Yii::t('app', 'Phone is invalid')],
            ['email', 'email'],
            ['currency', 'in', 'range' => ['RUB', 'EUR']],
            ['price', 'number'],
            ['count', 'integer'],
            ['prepayment', 'number'],
            ['prepayment_percent', 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'  => Yii::t('app', 'BookingForm.name'),
            'phone' => Yii::t('app', 'BookingForm.phone'),
            'email' => Yii::t('app', 'BookingForm.email'),
            'date'  => Yii::t('app', 'BookingForm.date'),
        ];
    }

    public function __construct(Excursion $excursion, $config = [])
    {
        $this->_excursion = $excursion;
        parent::__construct($config);
    }

    public function init()
    {
        $nearestDates = $this->_excursion->getNearestDates();
        $this->dates_available = join(',', array_map(function ($dateString) {
            $dateAndTime = preg_split('/\s/', $dateString);
            return $dateAndTime[0];
        }, $nearestDates));
        parent::init();
    }

    public function createOrder()
    {
        $order = new Order([
            'excursion_id' => $this->_excursion->id,
            'quantity'     => $this->count,
            'price'        => ceil(($this->count * $this->price * 100)),
            'currency'     => $this->currency,
            'date'         => $this->date,
            'name'         => $this->name,
            'phone'        => $this->phone,
            'email'        => $this->email,
            'status'       => Order::STATUS_CREATED,
            'language_code'=> Yii::$app->language,
            'prepayment'   => ceil(round($this->count * $this->prepayment, 2)),
            'prepayment_percent'=> $this->prepayment_percent,
        ]);
        $order->scenario = Order::SCENARIO_GUIDE;
        return $order->save(false)
             ? $order
             : null
        ;
    }
}
