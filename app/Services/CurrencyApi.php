<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use App\Models\Currency;

class CurrencyApi
{
    private Collection $currenciesCode;
    private array $currencyToUpsert;

    public function setCurrenciesCode(Collection $currenciesCode)
    {
        $this->currenciesCode = $currenciesCode;
    }

    public function setCurrencyToUpsert(Array $currencyUpsert)
    {
        $this->currencyToUpsert = $currencyUpsert;
    }

    public function getCurrencyToUpsert()
    {
        return $this->currencyToUpsert;
    }

    public function getCurrenciesCode()
    {
        return $this->currenciesCode;
    }

    public function updateCurrenciesFromApi()
    {
        $currencyData = [];
        //pobieram kody walut
        $carriencies = $this->getCurrenciesCode();
        foreach($carriencies as $currency)
        {
            //odpytuje po api dla danego kodu waluty po nazwę oraz mida
            $client = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get('http://api.nbp.pl/api/exchangerates/rates/a/'.$currency.'/');
            if($client->successful())
            {
                $currencyData[$currency]['mid'] = $client->object()->rates[0]->mid ?? null;
                $currencyData[$currency]['name'] = $client->object()->currency ?? null;
                
                //odpytuje po api dla danego kodu waluty po bid i ask
                $client = Http::withHeaders([
                    'Accept' => 'application/json',
                ])->get('http://api.nbp.pl/api/exchangerates/rates/c/'.$currency.'/');
                if($client->successful())
                {
                    $currencyData[$currency]['bid'] = $client->object()->rates[0]->bid ?? null;
                    $currencyData[$currency]['ask'] = $client->object()->rates[0]->ask ?? null;
                }
            }
        }
        //ustawiam tablicę walut do dodania lub aktualizacji
        $this->setCurrencyToUpsert($currencyData);
        //aktualizuję lub dodaję waluty
        $this->upsertCurrencies();
    }

    public function upsertCurrencies()
    {
        //pobieram waluty do dodania lub aktualizacji
        $currencyToUpsert = $this->getCurrencyToUpsert();
        foreach($currencyToUpsert as $key => $curr)
        {
            //jeśli waluta z takim kodem istnieje, to zaktualizuje, jeśli nie to doda nowy rekord
            $currency = Currency::updateOrCreate(
                ['code' => $key],
                ['name' => $curr['name'], 'mid' => $curr['mid'], 'bid' => $curr['bid'], 'ask' => $curr['ask']]
            );
        }
    }
}