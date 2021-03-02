<?php

// Observer

class Vacancy
{
    public String $title;
    public Int $experience;

    public function __construct(String $title, Int $experience)
    {
        $this->title = $title;
        $this->experience = $experience;
    }
}

class JobSeeker
{
    protected Int $id;
    public String $name;
    public String $email;
    public Int $experience;
    public String $desiredPosition;

    public function __construct($id, $name, $email, $experience, $desiredPosition)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->experience = $experience;
        $this->desiredPosition = $desiredPosition;
    }

    public function handle(Vacancy $vacancy)
    {
        echo $this->name . " вакансию " . serialize($vacancy) . " получил." . PHP_EOL;
    }
}

class HandHunter
{
    public Array $vacancies;
    protected Array $observers;

    public function addObserver(JobSeeker $jobSeeker): void
    {
        $this->observers[] = $jobSeeker;
    }

    public function removeObserver(JobSeeker $jobSeeker): void
    {
        foreach($this->observers as &$observer) {
            if ($observer === $jobSeeker) {
                unset($observer);
            }
        }
    }

    protected function notify(Vacancy $vacancy)
    {
        foreach ($this->observers as $observer) {
            if ($observer->desiredPosition !== $vacancy->title) {
                continue;
            }

            if ($observer->experience < $vacancy->experience) {
                continue;
            }

            $observer->handle($vacancy);
        }
    }

    public function publish(Vacancy $vacancy): void
    {
        $this->vacancies[] = $vacancy;
        $this->notify($vacancy);
    }
}


$handHunter = new HandHunter();

$jobSeeker1 = new JobSeeker(1, 'Hugo', 'hugo@google.com', 3, 'JS-programmer');
$jobSeeker2 = new JobSeeker(2, 'Umberto', 'umberto@google.com', 5, 'PHP-programmer');
$jobSeeker3= new JobSeeker(3, 'Diego', 'diego@google.com', 1, 'PHP-programmer');
$jobSeeker4= new JobSeeker(4, 'Hose', 'hose@google.com', 3, 'PHP-programmer');

$handHunter->addObserver($jobSeeker1);
$handHunter->addObserver($jobSeeker2);
$handHunter->addObserver($jobSeeker3);
$handHunter->addObserver($jobSeeker4);

$vacancy1 = new Vacancy('JS-programmer', 1);
$vacancy2 = new Vacancy('PHP-programmer', 3);

$handHunter->publish($vacancy1);
$handHunter->publish($vacancy2);