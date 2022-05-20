<?php

namespace App\Http\Controllers\Scrape;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use KubAT\PhpSimple\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ClearFileRateJob;

class ScrapeController extends Controller
{
    public function scraping()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://kursdollar.org/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $dom = HtmlDomParser::str_get_html($response);
        $rate_table = $dom->find('table[class=in_table]')[0];
        $rate_table_meta = $rate_table->find('tr');
        $day_time = explode('-', clear_string($rate_table_meta[0]->plaintext))[1];
        $meta =  array(
            "date"      => clear_string(explode(',', $day_time)[1]),
            "day"       => clear_string(explode(',', $day_time)[0]),
            "indonesia" => $rate_table_meta[1]->find('td')[1]->plaintext,
            "word"      => clear_string($rate_table_meta[1]->find('td')[2]->plaintext)
        );

        $rates = collect($rate_table->find('tr[id^=tr_id_]'))->map(function($row, $i) {
            $rate = $row->find('td');
            return (object) [
                "currency"  => clear_rate($rate[0]->plaintext),
                "buy"       => explode(" ", $rate[1]->plaintext)[0],
                "sell"      => explode(" ", $rate[2]->plaintext)[0],
                "average"   => explode(" ", $rate[3]->plaintext)[0],
                "word_rate" => $rate[4]->plaintext
            ];
        });

        $res = array_merge(["meta" => $meta], ["rate" => $rates->toArray()]);
        $filename = "rate-".date("d-m-Y--H-i-s").".json";
        Storage::disk('public')->put("scraping/$filename", json_encode($res));

        return redirect()->back()->withSuccess("Scraping berhasil, $filename berhasil disimpan");
    }

    public function clearRate()
    {
        ClearFileRateJob::dispatch();
        return redirect()->back()->withSuccess('Proses berhasil ditambahkan pada Job');
    }
}
