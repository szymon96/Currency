<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Services\CurrencyApi;
use App\Services\CalculateCurrency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('currency.index',[
            'currencies' => Currency::all()->map(function ($currency) {
                $currency->mid = number_format($currency->mid, 2);
                $currency->bid = number_format($currency->bid, 2);
                $currency->ask = number_format($currency->ask, 2);
                return $currency;
            })
        ]);
    }

    public function upsertCurrencies()
    {
        //tutaj tak naprawdę powinno brać z bazy, a użytkownik może dodać walutę, wpisujac tylko kod waluty, a reszta zaciągnie się samo. Tworzę kolekcję z kodami walut
        $currenciesCode = collect(['eur', 'usd', 'chf']);
        //Tworzenie nowego obiektu klasy CurrencyApi
        $currencies = new CurrencyApi();
        //Ustawiam currenciesCode
        $currencies->setCurrenciesCode($currenciesCode);    
        //dodaje lub aktualizuje dane walut    
        $currencies->updateCurrenciesFromApi();        
    }
    
    public function convertCurrency(Currency $currency, $price)
    {
        //tworzę nowy obiekt z przesłą walutą i kwotą
        $convertCurrency= new CalculateCurrency($currency, $price);
        //zwracam przeliczone wartości
        return $convertCurrency->calculate();
    }
}
