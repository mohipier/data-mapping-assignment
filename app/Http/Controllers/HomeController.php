<?php

namespace App\Http\Controllers;

use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Strategy\ExchangeRateInput;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * @Get("/")
    */
    public function index(){
        /// sample json url
        $jsonUrl = 'http://api.nbp.pl/api/cenyzlota/2016-04-04/2016-04-30/?format=json';

        /// sample xml url
        $xmlUrl = 'http://api.nbp.pl/api/cenyzlota/2016-04-04/2016-04-30/?format=xml';

        /// get data from url
        $response = Http::get($jsonUrl);
        $contentType = $response->headers();

        /// convert data with the content type header
        $inputData = new ExchangeRateInput($response->getBody(), $contentType['Content-Type']);
        $data = $inputData->get();

        /// inset all data in database
        Rate::insert($data);

        /// get all rates from database
        $rates = Rate::all();

        /// return rates by rate resourse
        return RateResource::collection($rates);
    }
}
