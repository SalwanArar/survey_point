<?php

namespace App\Http\Controllers\API\v02;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistrationApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $request->validate(
            [
                'registration' => [
                    'bail',
                    'json',
                ]
            ]
        );
        $registration = json_decode($request->get('registration'), true);
        $registrationId = DB::table('registrations')
            ->where('user_id', '=', Auth::user()->getAuthIdentifier())
            ->value('user_id');
        $reviewId = DB::table('reviewed_registrations')->insertGetId(
            [
                'name' => $registration['name'],
                'contact_number' => $registration['contact_number'],
                'nationality' => $registration['nationality'],
                'gender' => $registration['gender'],
                'registration_id' => $registrationId,
            ]
        );
        return response($reviewId, 201);
    }

}
