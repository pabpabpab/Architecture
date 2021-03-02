<?php

// Strategy

class PaymentRequest
{
    public Int $total;
    public String $phoneNumber;
    public Array $details;

    public function __construct($total, $phoneNumber, $details)
    {
        $this->total = $total;
        $this->phoneNumber = $phoneNumber;
        $this->details = $details;
    }
}


interface IPaymentSystem
{
    public function createRequest(Int $total, String $phone);
    public function getResponse();
}

class Yandex implements IPaymentSystem
{
    protected PaymentRequest $request;
    protected const SERVER = 'https://money.yandex.ru/payment';

    public function createRequest($total, $phone): IPaymentSystem
    {
        $details = ['Детали запроса к Яндексу'];
        $this->request = new PaymentRequest($total, $phone, $details);
        return $this;
    }

    public function getResponse(): String
    {
        $sendRequest = "Отправка запроса " . serialize($this->request) . " на сервер Яндекса " . self::SERVER;
        return $sendRequest . " и получение ответа от Яндекса";
    }
}

class Qiwi implements IPaymentSystem
{
    protected PaymentRequest $request;
    protected const SERVER = 'https://qiwi.ru/payment';

    public function createRequest($total, $phone): IPaymentSystem
    {
        $details = ['Детали запроса к Qiwi'];
        $this->request = new PaymentRequest($total, $phone, $details);
        return $this;
    }

    public function getResponse(): String
    {
        $sendRequest = "Отправка запроса " . serialize($this->request) . " на сервер Qiwi " . self::SERVER;
        return $sendRequest . " и получение ответа от Qiwi";
    }
}

class Webmoney implements IPaymentSystem
{
    protected PaymentRequest $request;
    protected const SERVER = 'https://webmoney.ru/payment';

    public function createRequest($total, $phone): IPaymentSystem
    {
        $details = ['Детали запроса к Webmoney'];
        $this->request = new PaymentRequest($total, $phone, $details);
        return $this;
    }

    public function getResponse(): String
    {
        $sendRequest = "Отправка запроса " . serialize($this->request) . " на сервер Webmoney " . self::SERVER;
        return $sendRequest . " и получение ответа от Webmoney";
    }
}




class Order
{
    public Int $total;
    public Array $goods;
    public String $phoneNumber;
    public IPaymentSystem $paymentSystem;

    public function __construct($goods, $total, $phoneNumber, IPaymentSystem $paymentSystem)
    {
        $this->goods = $goods;
        $this->total = $total;
        $this->phoneNumber = $phoneNumber;
        $this->paymentSystem = $paymentSystem;
    }

    public function pay()
    {
        return $this->paymentSystem
            ->createRequest($this->total, $this->phoneNumber)
            ->getResponse();
    }
}


$order = new Order(['Товары'], 567, '89221751215', new Qiwi());
echo $order->pay();