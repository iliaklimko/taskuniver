<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use common\eventing\OrderEvents;
use common\eventing\events\OrderGuideAcceptEvent;
use common\eventing\events\OrderGuideRejectEvent;
use common\eventing\events\OrderPaidToGuideEvent;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property string  $code уникальный для регистрации в шлюзе оплаты
 * @property string  $status
 * @property integer $excursion_id
 * @property integer $quantity количество
 * @property integer $price в копейках или евроцентах
 * @property integer $currency валюта RUB или EUR
 * @property string  $date
 * @property string  $name
 * @property string  $phone
 * @property string  $email
 * @property integer $guide_status
 * @property string  $guide_message
 * @property boolean $paid_to_guide перечислены ли деньги гиду?
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $language_code
 * @property integer $prepayment
 * @property integer $prepayment_percent
 *
 * @property Excursion $excursion
 */
class Order extends \yii\db\ActiveRecord
{
    const SCENARIO_GUIDE = 'scenario.guide';

    const STATUS_CREATED = 'CREATED'; // зарегистрирован в платежном шлюзе, но не обработан
    const STATUS_CHARGED = 'CHARGED'; // завершен успешно, средства списаны

    const GUIDE_STATUS_NEW      = 0;
    const GUIDE_STATUS_ACCEPTED = 10;
    const GUIDE_STATUS_REJECTED = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['quantity', 'price', 'currency', 'name', 'phone', 'email', 'code'], 'required'],
            [['excursion_id', 'quantity', 'price', 'prepayment_percent', 'prepayment'], 'integer'],
            [['status', 'currency', 'date', 'name', 'phone', 'email', 'code'], 'string', 'max' => 255],
            [['excursion_id'], 'exist', 'skipOnError' => true, 'targetClass' => Excursion::className(), 'targetAttribute' => ['excursion_id' => 'id']],
            ['status', 'default', 'value' => self::STATUS_CREATED],
            ['quantity', 'default', 'value' => 1],
            ['guide_status', 'default', 'value' => self::GUIDE_STATUS_NEW],
            ['guide_status', 'in', 'range' => [self::GUIDE_STATUS_NEW, self::GUIDE_STATUS_ACCEPTED, self::GUIDE_STATUS_REJECTED]],
            [['guide_message'], 'trim'],
            [['guide_message'], 'required', 'on' => self::SCENARIO_GUIDE],
            ['paid_to_guide', 'boolean'],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExcursion()
    {
        return $this->hasOne(Excursion::className(), ['id' => 'excursion_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->code = $this->generateCode();
            }
            return true;
        }
        return false;
    }

    protected function generateCode()
    {
        $code = uniqid('ID_', true);
        return str_replace('.', '_', $code);
    }

    /**
     * @return bool
     */
    public function accept($reason)
    {
        $this->guide_status = self::GUIDE_STATUS_ACCEPTED;
        $this->guide_message = $reason;
        $updated = $this->save(false);
        if ($updated) {
            Yii::$app->trigger(
                OrderEvents::GUIDE_ACCEPT,
                new OrderGuideAcceptEvent([
                    'orderId' => $this->id,
                ])
            );
        }
        return $updated;
    }

    /**
     * @param string $reason
     * @return bool
     */
    public function reject($reason)
    {
        $this->guide_status = self::GUIDE_STATUS_REJECTED;
        $this->guide_message = $reason;
        $updated = $this->save(false);
        if ($updated) {
            Yii::$app->trigger(
                OrderEvents::GUIDE_REJECT,
                new OrderGuideRejectEvent([
                    'orderId' => $this->id,
                ])
            );
        }
        return $updated;
    }

    public function payToGuide()
    {
        $this->paid_to_guide = true;
        $updated = $this->save(false);
        if ($updated) {
            Yii::$app->trigger(
                OrderEvents::PAID_TO_GUIDE,
                new OrderPaidToGuideEvent([
                    'orderId' => $this->id,
                ])
            );
        }
        return $updated;
    }

    public static function getStatusList()
    {
        return [
            self::GUIDE_STATUS_NEW => Yii::t('app', 'New'),
            self::GUIDE_STATUS_ACCEPTED => Yii::t('app', 'Accepted'),
            self::GUIDE_STATUS_REJECTED => Yii::t('app', 'Rejected'),
        ];
    }
}
