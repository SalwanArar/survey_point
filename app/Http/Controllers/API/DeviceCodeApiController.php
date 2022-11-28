<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeviceCodeApiController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'mac_address' => [
                'bail',
                'required',
                'mac_address'
            ]
        ]);
        $macAddress = $request->get('mac_address');
        $deviceCodes = new DeviceCode();
        $deviceCode = DB::table($deviceCodes->getTable())
            ->where('mac_address', '=', $macAddress);
        if (!$deviceCode->exists()) {
            $deviceCodeId = DB::table($deviceCodes->getTable())->insertGetId([
                'mac_address' => $macAddress
            ]);
            $code = DB::table($deviceCodes->getTable())
                ->where('id', '=', $deviceCodeId)
                ->value('device_code');
            return response([
                'device_code_id' => $deviceCodeId,
                'device_code' => $code
            ], 201);
        }
        return response([
            'device_code_id' => $deviceCode->value('id'),
            'device_code' => $deviceCode->value('device_code')
        ], 200);
    }
}
