<?php


namespace App\Http\Controllers\API\Services;


use App\Enums\ApiResponseEnum;
use App\Enums\ServiceType;
use App\Rules\Phone;
use App\Services\SageCloud\SageCloudApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataController
{

    public function providers(): JsonResponse
    {
        $fields= ['type', 'name', 'narration', 'image'];
        $data_providers = DB::table('sage_cloud_data_providers')->select($fields)->get();
        return response()->json([
            'status' => ApiResponseEnum::success(),
            'message' => 'Data providers retrieved successfully',
            'data' => $data_providers,
        ]);
    }


    public function dataBundle($provider, Request $request)//: JsonResponse
    {
        $dataBundles = fetchDataBundles(strtoupper($provider));
        return response()->json([
            'status' => ApiResponseEnum::success(),
            'message' => 'Data bundles retrieved successfully',
            'data' => $dataBundles
        ]);
    }


    public function purchase(Request $request, SageCloudApiService $sageCloud): JsonResponse
    {
        $data = $request->validate([
            'mobile' => ['required', 'size:11', new Phone],
            'operator' => 'required',
            'bundle' => ['required', 'exists:sage_cloud_data_bundles,code'],
        ]);

        $payload = [
            'reference' => tx_ref(ServiceType::DATA()),
            'type' => strtoupper($request->operator.'data'),
            'network' => strtoupper($request->operator),
            'phone' => $request->mobile,
            'provider' => strtoupper($request->operator),
            'code' => $request->bundle,
        ];

        dispatch(function() use ($payload, $sageCloud){
            $response = $sageCloud->purchaseData($payload);
            logSlack('data-purchase', compact('response'));
        });

        return response()->json([
            'status' => ApiResponseEnum::success(),
            'message' => 'Data bundle purchase request submitted.',
        ]);
    }



}
