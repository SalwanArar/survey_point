<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\DeviceCode;

//use Illuminate\Contracts\Support\ValidatedData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

//use Illuminate\Validation\Rule;

class AuthorizationController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response('index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        return response($request, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param string $deviceCode
     * @return Response
     */
    public function show(string $deviceCode): Response
    {
        return response($deviceCode, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $deviceCodeId
     * @return Response
     */
    public function update(Request $request, int $deviceCodeId): Response
    {
        // validate the request
        $request->validate([
            'device_code' => [
                'bail',
                'required',
                'max:6',
                'min:6'
            ]
        ]);

        // check if device exists in DeviceCodes Table
        $modelDeviceCodes = new DeviceCode();
        $modelDevice = new Device();
        if (!DB::table($modelDeviceCodes->getTable())
            ->where('id', '=', $deviceCodeId)
            ->where('device_code', '=', $request->get('device_code'))
            ->exists())
            // TODO:: replace the phrase with empty list []
            return response('device not exists', 204);


        // get the device
        $device = DB::table($modelDevice->getTable())
            ->where($modelDeviceCodes->getForeignKey(), '=', $deviceCodeId);


        // check device existence
        if (!$device->exists())
            return response("device doesn't exists in Devices Table", 204);


        // check if the device has a token already
//        if (DB::table('personal_access_tokens')
//            ->where('tokenable_id', '=', $device->value('user_id'))
//            ->exists())
//            // TODO:: replace the phrase with empty list []
//            return response('token already exists', 204);

        // login to user and create new token
        /**
         * @param User $user
        */
        $user = Auth::loginUsingId($device->value('id'));
        $token = $user->createToken($device->value('device_name'))->plainTextToken;

        return response(
            [
                'token' => $token
            ],
            201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        return response('destroy '.$id, 204);
    }
}
