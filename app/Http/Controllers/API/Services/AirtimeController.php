<?php


namespace App\Http\Controllers\API\Services;


use App\Enums\ApiResponseEnum;
use App\Enums\ServiceType;
use App\Services\SageCloud\SageCloudApiService;
use Illuminate\Http\Request;

class AirtimeController
{

    private $service_code = [
        'mtn' => 'MTNVTU',
        'airtel' => 'AIRTELVTU',
        '9mobile' => '9MOBILEVTU',
        'glo' => 'GLOVTU',
    ];

    public function providers()
    {

    }


    public function purchase(Request $request, SageCloudApiService $sageCloud)
    {
        $request->validate([
            'mobile' => ['required'],
            'amount' => ['required', 'numeric', 'min:50', 'max:5000'],
            'operator' => ['required'],
        ]);
        $data = $request->all();


        dispatch(function () use ($data, $sageCloud){
            $response = $sageCloud->purchaseAirtime([
                'reference' => tx_ref(ServiceType::AIRTIME()->value),
                'network' => strtoupper($data['operator']),
                'service' => $this->service_code[$data['operator']],
                'phone' => $data['mobile'],
                'amount' => $data['amount']
            ]);
            logSlack('airtime-recharge', ['response' => $response]);
        });

        return response()->json([
            'status' => ApiResponseEnum::success(),
            'message' => 'Airtime request submitted',
        ]);
    }
}
