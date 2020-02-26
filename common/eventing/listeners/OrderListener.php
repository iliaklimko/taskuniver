<?php

namespace common\eventing\listeners;

use Yii;
use common\eventing\listeners\traits\SendMessageTrait;
use common\eventing\listeners\traits\SendSmsTrait;
use common\eventing\listeners\traits\CreateLinkTrait;
use common\eventing\events\OrderChargedEvent;
use common\eventing\events\OrderGuideAcceptEvent;
use common\eventing\events\OrderGuideRejectEvent;
use common\eventing\events\OrderPaidToGuideEvent;
use common\models\Order;
use common\models\EmailTemplate;

class OrderListener
{
    use SendMessageTrait, SendSmsTrait, CreateLinkTrait;

    public static function handlePaidToGuide(OrderPaidToGuideEvent $event)
    {
        if ($order = Order::findOne($event->orderId)) {
            $locale = $order->language_code;
            Yii::$app->language = $locale;
            self::sendMessage(
                $order->email,
                EmailTemplate::ALIAS_PAID_TO_GUIDE,
                [
                    '{{order_id}}'                  => $order->code,
                    '{{excursion_id}}'              => $order->excursion->id,
                    '{{excursion_title}}'           => $order->excursion->title,
                    '{{order_price}}'               => $order->currency . ' ' . Yii::$app->formatter->asDecimal($order->price/100, 2),
                    '{{order_person_number}}'       => $order->quantity,
                ],
                $locale
            );
        }
    }

    public static function handleCharge(OrderChargedEvent $event)
    {
        if ($order = Order::findOne($event->orderId)) {
            $locale = $order->language_code;
            Yii::$app->language = $locale;
            $body = $order->price * 100 > 0
                ? Yii::t('app', 'PaymentSuccessPopup.body: {orderCode} {excursionTitle} {orderQuantity} {orderDate} {orderName} {orderPrice} {orderEmail} {orderPhone}', [
                    'orderCode' => $order->code,
                    'excursionTitle' => $order->excursion->title,
                    'orderQuantity' => $order->quantity,
                    'orderDate' => $order->date ? Yii::t('app', 'PaymentSuccess.date {date}', ['date' => $order->date]) : null,
                    'orderName' => $order->name,
                    'orderPrice' => $order->price > 0
                        ? '<li>' . ($locale == 'ru' ? 'Стоимость заказа: ' : 'Price: ') . Yii::$app->formatter->asDecimal($order->price/100, 2) . ' ' .($order->currency == 'EUR' ? '&euro;' : 'руб.') . '</li>'
                        : null,
                    'orderEmail' => $order->email,
                    'orderPhone' => $order->phone,
                ])
                : Yii::t('app', 'PaymentFreeSuccessPopup.body: {orderCode} {excursionTitle} {orderQuantity} {orderDate} {orderName} {orderPrice} {orderEmail} {orderPhone}', [
                    'orderCode' => $order->code,
                    'excursionTitle' => $order->excursion->title,
                    'orderQuantity' => $order->quantity,
                    'orderDate' => $order->date ? Yii::t('app', 'PaymentSuccess.date {date}', ['date' => $order->date]) : null,
                    'orderName' => $order->name,
                    'orderPrice' => $order->price > 0
                        ? '<li>' . ($locale == 'ru' ? 'Стоимость заказа: ' : 'Price: ') . Yii::$app->formatter->asDecimal($order->price/100, 2) . ' ' . ($order->currency == 'EUR' ? '&euro;' : 'руб.') . ';</li>'
                        : null,
                    'orderEmail' => $order->email,
                    'orderPhone' => $order->phone,
                ]);
            Yii::$app->mailer->compose(
                ['html'    => 'raw-content-html'],
                ['content' => $body]
            )
                ->setFrom(Yii::$app->params['supportEmail'])
                ->setTo($order->email)
                ->setSubject($locale == 'ru' ? 'Заказ оплачен' : 'Order charged')
                ->send();
            // self::sendMessage(
            //     $order->email,
            //     EmailTemplate::ALIAS_ORDER_CHARGED_TO_USER,
            //     [
            //         '{{order_id}}'                  => $order->code,
            //         '{{excursion_title}}'           => $order->excursion->translate('ru')->title,
            //         '{{order_person_number}}'       => $order->quantity,
            //         '{{order_date}}'                => $order->date,
            //         '{{order_price}}'               => $order->currency . ' ' . $order->price,
            //     ]
            // );
            $profileLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'user/update',
                'id' => $order->excursion->user->id,
            ]);
            $excursionLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'excursion/update',
                'id' => $order->excursion->id,
            ]);
            self::sendMessage(
                Yii::$app->params['supportEmail'],
                EmailTemplate::ALIAS_ORDER_CHARGED_TO_ADMIN,
                [
                    '{{excursion_title}}' => $order->excursion->title,
                    '{{profile_link}}'    => self::createLink($profileLink),
                    '{{excursion_link}}'  => self::createLink($excursionLink),
                ],
                'en'
            );
            $orderLink = Yii::$app->urlManager->createAbsoluteUrl([
                'user/list-orders',
            ]);
            self::sendMessage(
                $order->excursion->user->email,
                EmailTemplate::ALIAS_ORDER_CHARGED_TO_GUIDE,
                [
                    '{{order_id}}'                  => $order->code,
                    '{{customer_name}}'             => $order->name,
                    '{{excursion_title}}'           => $order->excursion->title,
                    '{{order_person_number}}'       => $order->quantity,
                    '{{order_date}}'                => $order->date ? $order->date : null,
                    '{{order_email}}'               => $order->email,
                    '{{order_phone}}'               => $order->phone,
                    '{{order_link}}'                => self::createLink($orderLink),
                    '{{order_price}}'               => $order->price > 0
                                                        ? ($locale == 'ru' ? 'Стоимость заказа: ' : 'Price: ') . Yii::$app->formatter->asDecimal($order->price/100, 2) . ' ' . ($order->currency == 'EUR' ? '&euro;' : 'руб.')
                                                        : null,
                ],
                $locale
            );
            self::sendSms(
                $order->excursion->user->getPhoneNumber(),
                Yii::t(
                    'app',
                    'SmsToGuide {orderCode} {orderPrice} {excursionTitle} {orderQuantity} {orderDate}',
                    [
                        'orderCode'      => $order->code,
                        'excursionTitle' => $order->excursion->title,
                        'orderQuantity'  => $order->quantity,
                        'orderDate'      => $order->date,
                        'orderPrice'     => $order->currency . ' ' . $order->price/100,
                    ]
                )
            );
        }
    }

    public static function handleGuideAccept(OrderGuideAcceptEvent $event)
    {
        if ($order = Order::findOne($event->orderId)) {
            $locale = $order->language_code;
            Yii::$app->language = $locale;
            $ordersLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'order/index',
            ]);
            $profileLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'user/update',
                'id' => $order->excursion->user->id,
            ]);
            self::sendMessage(
                Yii::$app->params['supportEmail'],
                EmailTemplate::ALIAS_ORDER_GUIDE_ACCEPT,
                [
                    '{{full_name}}'    => $order->excursion->user->full_name,
                    '{{excursion_id}}' => $order->excursion->id,
                    '{{details}}'      => $order->guide_message,
                    '{{profile_link}}' => self::createLink($profileLink),
                    '{{orders_link}}'  => self::createLink($ordersLink),
                ],
                'en'
            );
            self::sendMessage(
                $order->email,
                EmailTemplate::ALIAS_ORDER_GUIDE_ACCEPT_TO_USER,
                [
                    '{{profile_link}}' => self::createLink($profileLink),
                    '{{orders_link}}'  => self::createLink($ordersLink),
                    '{{order_id}}'     => $order->code,
                    '{{details}}'      => $order->guide_message,
                ],
                $locale
            );
        }
    }

    public static function handleGuideReject(OrderGuideRejectEvent $event)
    {
        if ($order = Order::findOne($event->orderId)) {
            $locale = $order->language_code;
            Yii::$app->language = $locale;
            $ordersLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'order/index',
            ]);
            $profileLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'user/update',
                'id' => $order->excursion->user->id,
            ]);
            self::sendMessage(
                Yii::$app->params['supportEmail'],
                EmailTemplate::ALIAS_ORDER_GUIDE_REJECT,
                [
                    '{{full_name}}'           => $order->excursion->user->full_name,
                    '{{excursion_id}}'        => $order->excursion->id,
                    '{{details}}'             => $order->guide_message,
                    '{{profile_link}}'        => self::createLink($profileLink),
                    '{{orders_link}}'         => self::createLink($ordersLink),
                    '{{excursion_title}}'     => $order->excursion->title,
                    '{{order_person_number}}' => $order->quantity,
                    '{{order_date}}'          => $order->date,
                    '{{order_price}}'         => $order->currency . ' ' . $order->price/100,
                    '{{order_phone}}'         => $order->phone,
                    '{{order_email}}'         => $order->email,
                ],
                'en'
            );
            self::sendMessage(
                $order->email,
                EmailTemplate::ALIAS_ORDER_GUIDE_REJECT_TO_USER,
                [
                    '{{profile_link}}'        => self::createLink($profileLink),
                    '{{orders_link}}'         => self::createLink($ordersLink),
                    '{{order_id}}'            => $order->code,
                    '{{details}}'             => $order->guide_message,
                    '{{excursion_title}}'     => $order->excursion->title,
                    '{{order_person_number}}' => $order->quantity,
                    '{{order_date}}'          => $order->date,
                    '{{order_price}}'         => $order->currency . ' ' . $order->price,
                    '{{support_email}}'       => Yii::$app->params['supportEmail'],
                ],
                $locale
            );
        }
    }
}
