<?php
namespace App\Services;

class CalculateCurrency
{
    private object $currency;
    private float $price;

    public function __construct($currency, $price)
    {
        $this->currency = $currency;
        $this->price = $price;
    }

    // metoda przeliczająca na podstawie podane waluty i kwoty do PLN
    public function calculate()
    {
        if($this->price > 0 )
        {
            $currency['mid'] = number_format($this->currency->mid * $this->price, 2).' PLN';
            $currency['bid'] = number_format($this->currency->bid * $this->price, 2).' PLN';
            $currency['ask'] = number_format($this->currency->ask * $this->price, 2).' PLN';
            return json_encode($currency);
        }
        return 'Kwota musi być większa od 0';
    }
}
