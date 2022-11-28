<?php

namespace App\Http\Controllers\API\v02;

use App\Http\Controllers\Controller;
use App\Models\DeviceCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeviceCodeApiV2Controller extends Controller
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
            'serial_number' => [
                'bail',
                'required',
                'min:16',
                'max:16',
            ]
        ]);
        $serialNumber = $request->get('serial_number');
        $deviceCodes = new DeviceCode();
        $deviceCode = DB::table($deviceCodes->getTable())
            ->where('serial_number', '=', $serialNumber);
        if (!$deviceCode->exists()) {
            $deviceCodeId = DB::table($deviceCodes->getTable())->insertGetId([
                'serial_number' => $serialNumber
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
