<?php
/*
spl_autoload_register(function ($classname) {
   require_once ($classname.'.php');
});
*/

interface ISender
{
    public function send();
}

class Stub implements ISender
{
    public function send() {}
}

class Mail implements ISender
{
    protected ISender $next;

    public function __construct(ISender $nextSender)
    {
        $this->next = $nextSender;
    }

    public function send()
    {
        echo "Mail notice" . PHP_EOL;
        $this->next->send();
    }
}

class Vk implements ISender
{
    protected ISender $next;

    public function __construct(ISender $nextSender)
    {
        $this->next = $nextSender;
    }

    public function send()
    {
        echo "Vk notice" . PHP_EOL;
        $this->next->send();
    }
}

class Telegram implements ISender
{
    protected ISender $next;

    public function __construct(ISender $nextSender)
    {
        $this->next = $nextSender;
    }

    public function send()
    {
        echo "Telegram notice" . PHP_EOL;
        $this->next->send();
    }
}


// ----- потом так -----
$selectedMethods = ['Mail', 'Vk', 'Telegram'];
$notifier = new Stub();
foreach ($selectedMethods as $method) {
    $notifier = new $method($notifier);
}
$notifier->send();


// ----- или так -----
$notifier = new Telegram(new Vk(new Mail(new Stub())));
$notifier->send();