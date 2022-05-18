<?php

use App\Enums\ServiceType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

function logSlack($message, $data) {
    Log::channel('slack')->critical($message, $data);
}


function tx_ref(string $service_type = null): string{
    $service_types = [
        ServiceType::AIRTIME()->value => 'AIRT',
        ServiceType::BETTING()->value => 'BETG',
        ServiceType::DATA()->value => 'DATA',
        ServiceType::BANK_TRANSFER()->value => 'TRSF',
        ServiceType::CABLE_TV()->value => 'CBLE',
        ServiceType::ELECTRICITY()->value => 'ELEC',
        ServiceType::EPIN()->value => 'EPIN',
    ];
    $leading = 'PAYNCLE';
    $time = substr(time(), -4);
    $str = Str::upper(Str::random(4));
    $service_type = array_key_exists($service_type, $service_types) ? $service_types[$service_type] : 'TRNX';

    return sprintf(
        '%s|%s|%s%s',
        $leading,
        $service_type ?? "rand(1111, 9999)",
        $time,
        $str
    );
};


function fetchDataBundles($provider): array
{
    return DB::table('sage_cloud_data_bundles')
        ->select(['id', 'type', 'code', 'description', 'amount', 'price', 'value', 'duration'])
        ->where('type', $provider)
        ->orderBy('amount', 'ASC')
        ->get()
        ->toArray();
}

function fetchDataBundleRow($code)
{
    return DB::table('sage_cloud_data_bundles')->whereCode($code)->first();
}
