<?php

// Внешняя библиотека

class CircleAreaLib
{
    public function getCircleArea(int $diagonal)
    {
        $area = (M_PI * $diagonal**2)/4;

       return $area;
   }
}

class SquareAreaLib
{
    public function getSquareArea(int $diagonal)
    {
        $area = ($diagonal**2)/2;

        return $area;
    }
}



// Внутренняя библиотека

// Имеющиеся интерфейсы

interface ICircle
{
    function circleArea(int $circumference);
}

interface ISquare
{
    function squareArea(int $squareSide);
}


// Адаптеры

class CircleAdapter implements ICircle
{
    private CircleAreaLib $circleLib;

    public function __construct(CircleAreaLib $circleLib)
    {
        $this->circleLib = $circleLib;
    }

    public function circleArea(int $circumference)
    {
        $diagonal = (int) $circumference/M_PI;
        return $this->circleLib->getCircleArea($diagonal);
    }
}

class SquareAdapter implements ISquare
{
    private SquareAreaLib $squareLib;

    public function __construct(SquareAreaLib $squareLib)
    {
        $this->squareLib = $squareLib;
    }

    public function squareArea(int $squareSide)
    {
        $diagonal = (int) sqrt(2) * $squareSide;
        return $this->squareLib->getSquareArea($diagonal);
    }
}




// Клиентский код

$circumference = 5;
$circleAdapter  = new CircleAdapter(new CircleAreaLib());
$area = $circleAdapter->circleArea($circumference);


$squareSide = 5;
$squareAdapter  = new SquareAdapter(new SquareAreaLib());
$area = $squareAdapter->squareArea($squareSide);