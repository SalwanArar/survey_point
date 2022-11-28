<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\AnswerTranslation;
use App\Models\Device;
use App\Models\DeviceCode;
use App\Models\DeviceLanguages;
use App\Models\Question;
use App\Models\QuestionTranslation;
use App\Models\Survey;
use App\Models\TypeOfQuestion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeviceApiController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        // validate the request
        $request->validate([
            'device_code' => [
                'bail',
                'required',
                'max:6',
                'min:6'
            ],
            'device_code_id' => [
                'bail',
                'required',
                'integer'
            ]
        ]);
        $deviceCodeId = $request->get('device_code_id');

        // check if device exists in DeviceCodes Table
        $modelDeviceCodes = new DeviceCode();
        $modelDevice = new Device();
        if (!DB::table($modelDeviceCodes->getTable())
            ->where('id', '=', $deviceCodeId)
            ->where('device_code', '=', $request->get('device_code'))
            ->exists())
            // device not exists
            return response([], 204);

        // get the device
        $device = DB::table($modelDevice->getTable())
            ->where($modelDeviceCodes->getForeignKey(), '=', $deviceCodeId);

        // check device existence
        if (!$device->exists())
            // device doesn't exists in Device Table
            return response([], 204);

        // check if the device has a token already
        if (DB::table('personal_access_tokens')
            ->where('tokenable_id', '=', $device->value('user_id'))
            ->where('device_id', '=', $device->value('id'))
            ->exists())
            // Token already exists
            return response([], 200);

        // login to user and create new token
        $user = Auth::loginUsingId($device->value('user_id'));
        $token = $user->createToken(
            $device->value('device_name'),
            ['*'],
            $device->value('id')
        )->plainTextToken;

        return response(
            [
                'token' => $token
            ],
            201);
    }


    /**
     * Get the survey of the attached device id.
     *
     * @param string $date
     * @return Response
     */
    public function index(string $date): Response
    {
        if ($date != null) {
            Validator::validate(['date' => $date], ['date' => 'date']);
            $updatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d H:i:s');
        } else {
            $updatedAt = '';
        }

        $user = Auth::user();
        $deviceId = $user->currentAccessToken()->device_id;
        $device = DB::table('devices')->where('id', '=', $deviceId);

        if ($device->value('updated_at') == $updatedAt) return response([], 204);

        $organizationName = DB::table('devices')
            ->where('id', '=', $deviceId)
            ->value('business_name');

        $deviceLogoUrl = DB::table('pictures')
            ->where('id', '=', $device->value('picture_id'))
            ->value('pic_url');

        $modelDeviceLanguages = new DeviceLanguages();
        $deviceLanguages = DB::table($modelDeviceLanguages->getTable())
            ->where('device_id', '=', $deviceId)
            ->get(['device_local']);
        $deviceLang = [];
        foreach ($deviceLanguages as $lang) $deviceLang[] = $lang->device_local;

        if (($surveyId = $device->value('survey_id')) != null) {
            return $this->getSurvey(
                $surveyId,
                $device->value('updated_at'),
                $device->value('device_name'),
                $organizationName,
                $deviceLang,
                $device->value('out_of_service'),
                $deviceLogoUrl,
            );
        } else if (($registrationId = $device->value('registration_id')) != null) {
            return $this->getRegistration(
                $user->getAuthIdentifier(),
                $organizationName,
                $device->value('updated_at'),
                $registrationId,
                $deviceLang,
                $device->value('out_of_service'),
                $deviceLogoUrl,
            );
        }
        $outOfService = true;
        if ($device->value('out_of_service') == 0) {
            $outOfService = false;
        }
        return response(
            [
                'device_locals' => $deviceLang,
                'device_logo' => $deviceLogoUrl,
                'out_of_service' => $outOfService,
                'organization_name' => $organizationName,
                '$deviceId'=>$deviceId,
                'currentAccessToken'=>$user->currentAccessToken()->device_id,
                'updated_at' => $device->value('updated_at'),
                'business' => null,
                'registration' => null,
            ],
            200,
        );
    }


    /**
     *
     * Change device out of service status
     *
     * @param Request $request
     * @return Response
     *
     */
    public function storeStatus(Request $request): Response
    {
        // validate the request
        $request->validate([
            'status' => [
                'bail',
                'required',
                'max:1',
            ],
        ]);

        $outOfService = $request->get('status');


//        $deviceId = DB::table('personal_access_tokens')
//            ->where('tokenable_id', '=', Auth::user()->getAuthIdentifier())
//            ->value('device_id');

        $deviceId = Auth::user()->currentAccessToken()->device_id;
        DB::table('devices')
            ->where('id', '=', $deviceId)
            ->update([
                'out_of_service' => $outOfService,
            ]);
        return response(
            [],
            201);
    }


    /**
     * Get the registration
     *
     * @param int $userId
     * @param string $organizationName
     * @param string $updateAt
     * @param int $registrationId
     * @param array $deviceLang
     * @param bool $outOfService
     * @param string|null $deviceLogoUrl
     *
     * @return Response
     *
     */
    private function getRegistration(
        int         $userId,
        string      $organizationName,
        string      $updateAt,
        int         $registrationId,
        array       $deviceLang,
        bool        $outOfService,
        string|null $deviceLogoUrl,
    ): Response
    {
        $registration = DB::table('registrations')
            ->where('user_id', '=', $userId)
            ->get(
                [
                    'name',
                    'birthdate',
                    'gender',
                    'phone_number',
                    'nationality',
                    'residence_state',
                    'comment',
                    'logo_id',
                ]);
        $registrationTranslation = DB::table('registration_translations')
            ->where('registration_id', '=', $registrationId)
            ->get(
                [
                    'registration_details',
                    'registration_local',
                ]);
//        $temp = [];
//        foreach ($registrationTranslation as $reg) {
//            $temp[] = [$reg->registration_local => $reg->registration_details];
//        }

        $registration[0]->translations = $registrationTranslation;

        return response(
            [
                'device_locals' => $deviceLang,
                'device_logo' => $deviceLogoUrl,
                'out_of_service' => $outOfService,
                'organization_name' => $organizationName,
                'updated_at' => $updateAt,
                'business' => null,
                'registration' => $registration[0],
            ],
            200,
        );
    }


    /**
     * Get the survey of the attached device id.
     *
     * @param int $surveyId
     * @param string $updatedAt
     * @param string $deviceName
     * @param string $organizationName
     * @param array $deviceLang
     * @param bool $outOfService
     * @param string|null $deviceLogoUrl
     *
     * @return Response
     */
    private function getSurvey(
        int         $surveyId,
        string      $updatedAt,
        string      $deviceName,
        string      $organizationName,
        array       $deviceLang,
        bool        $outOfService,
        string|null $deviceLogoUrl
    ): Response
    {
        $modelSurvey = new Survey();
        $survey = DB::table($modelSurvey->getTable())
            ->where('id', '=', $surveyId)
            ->get([
                'id',
                'survey_name',
                'customer_info',
                'contact_number',
                'comment',
                'user_id',
                'logo_id',
                'updated_at',
            ]);

        $modelQuestionType = new TypeOfQuestion();
        $modelQuestion = new Question();
        $modelQuestionTranslation = new QuestionTranslation();
        $modelAnswer = new Answer();
        $modelAnswerTranslation = new AnswerTranslation();

        $questions = DB::table($modelQuestion->getTable())
            ->where($modelSurvey->getForeignKey(), '=', $surveyId)
            ->join(
                $modelQuestionType->getTable(),
                $modelQuestionType->getTable() . '.id',
                '=',
                $modelQuestion->getTable() . '.' . $modelQuestionType->getForeignKey(),
            )->get(
                [
                    'questions.id',
                    $modelQuestionType->getTable() . '.question_type',
                    'questions.question_id',
                ]
            );

        $question_answers = [];
        $customIds = [];
        foreach ($questions as $question) {
            $questionType = $question->question_type;
            if ($questionType === 'custom_rating' ||
                $questionType === 'custom_satisfaction') {
                $customQuestion = DB::table('custom_questions')
                    ->where('question_id', '=', $question->id)
                    ->get([
                        'id',
                        'picture_id',
                        'custom_question_id',
                        'question_id',
                    ]);
                if (!in_array($customQuestion[0]->custom_question_id, $customIds)) {
                    $customIds[] = $customQuestion[0]->custom_question_id;


                    $customTranslations = DB::table('custom_question_translations')
                        ->where('custom_question_id', '=', $customQuestion[0]->id)
                        ->get();


                    $questions_translations = [];
                    foreach ($customTranslations as $customTrans) {
                        $questions_translations[] = [
                            'question_local' => $customTrans->question_local,
                            'question_details' => $customTrans->question_details,
                        ];
                    }

                    $allRelatedCustomQuestion = DB::table('custom_questions')
                        ->where('custom_question_id', '=', $customQuestion[0]->id)
                        ->get();

                    $answers = [];
                    foreach ($allRelatedCustomQuestion as $related) {
                        $relatedTranslations = DB::table('question_translations')
                            ->where('question_id', '=', $related->question_id)
                            ->get();
                        $questionLanguages = [];
                        foreach ($relatedTranslations as $relatedTranslation) {
                            $questionLanguages[] = [
                                'answer_details' => $relatedTranslation->question_details,
                                'answer_local' => $relatedTranslation->question_local,
                            ];
                        }
                        $answerPicUrl = DB::table('pictures')
                            ->where('id', '=', $related->picture_id)
                            ->value('pic_url');
                        $answers[] = [
                            'id' => $related->question_id,
                            'conditional' => false,
                            'picture_id' => $answerPicUrl,
                            'languages' => $questionLanguages,
                        ];
                    }

                    $question_answers[] = [
                        'question' => [
                            'question_id' => $customQuestion[0]->question_id,
                            'question_type' => $question->question_type,
                            'conditional_id' => null,
                            'language' => $questions_translations,
                        ],
                        'answers' => $answers,
                    ];
                }
            } else {
                $questionTranslations = DB::table($modelQuestionTranslation->getTable())
                    ->where($modelQuestion->getForeignKey(), '=', $question->id)
                    ->get([
                        'question_details',
                        'question_local',
                    ]);

                $questions_translations = [];

                for ($k = 0; $k < count($questionTranslations); $k++) {
                    $questions_translations[$k] = [
                        'question_local' => $questionTranslations[$k]->question_local,
                        'question_details' => $questionTranslations[$k]->question_details,
                    ];
                }

                $answers = [];
                if ($questionType === 'yes_no') {
                    $answers[] = [
                        'id' => 1,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 2,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                } else if ($questionType === 'rating') {
                    $answers[] = [
                        'id' => 3,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 4,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 5,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 6,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 7,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                } else if ($questionType === 'satisfaction') {
                    $answers[] = [
                        'id' => 8,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 9,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 10,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 11,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                    $answers[] = [
                        'id' => 12,
                        'conditional' => false,
                        'picture_id' => null,
                        'languages' => [],
                    ];
                } else {
                    $answers = DB::table($modelAnswer->getTable())
                        ->where($modelQuestion->getForeignKey(), '=', $question->id)
                        ->select([
                            $modelAnswer->getTable() . '.id',
                            $modelAnswer->getTable() . '.picture_id',
                            $modelAnswer->getTable() . '.conditional',
                        ])
                        ->get();

                    for ($i = 0; $i < count($answers); $i++) {
                        $translations = DB::table($modelAnswerTranslation->getTable())
                            ->where($modelAnswer->getForeignKey(), '=', $answers[$i]->id)
                            ->get([
                                'answer_details',
                                'answer_local',
                            ]);

                        $answerUrl = DB::table('pictures')
                            ->where('id', '=', $answers[$i]->picture_id)
                            ->value('pic_url');

                        $conditionalAnswer = true;
                        if ($answers[$i]->conditional == 0) {
                            $conditionalAnswer = false;
                        }
                        $answers[$i] = [
                            'id' => $answers[$i]->id,
                            'conditional' => $conditionalAnswer,
                            'picture_id' => $answerUrl,
                            'languages' => $translations,
                        ];
                    }
                }


                $question_answers[] = [
                    'question' => [
                        'question_id' => $question->id,
                        'question_type' => $question->question_type,
                        'conditional_id' => $question->question_id,
                        'language' => $questions_translations,
                    ],
                    'answers' => $answers,
                ];
            }
        }
        $conditionalAnswers = DB::table('conditional_answers')
            ->where('survey_id', '=', $surveyId)
            ->get();
        foreach ($conditionalAnswers as $conditionalAnswer) {
            $index = $this->getQuestionIndexById($conditionalAnswer->question_id, $question_answers);
//            $question_answers[$index]['answers']->map(function ($object) {
//                if($object['id'] == $this->conditionalAnswer->answer_id){}
//                return $object;
//            });
//            for ($i = 0; $i < count($question_answers[$index]['answers']); $i++) {
//                if ($question_answers[$index]['answers'][$i]['id'] === $conditionalAnswer->answer_id) {
//                    $question_answers[$index]['answers'][$i]['conditional'] = true;
//                    break;
//                }
//            }
            foreach ($question_answers[$index]['answers'] as $answerIndex => $answer) {
                if ($question_answers[$index]['answers'][$answerIndex]['id'] == $conditionalAnswer->answer_id) {
                    $answer['conditional'] = true;
                    $question_answers[$index]['answers'][$answerIndex] = $answer;
                    break;
                }
            }
        }
        return response([
            'device_locals' => $deviceLang,
            'device_logo' => $deviceLogoUrl,
            'out_of_service' => $outOfService,
            'organization_name' => $organizationName,
            'device_name' => $deviceName,
            'updated_at' => $updatedAt,
            'business' => [
                'survey' => $survey[0],
                'questions' => $question_answers,
            ],
            'registration' => null,
        ], 200);
    }

    /**
     * Get question by id from list of questions
     *
     * @param int $questionId
     * @param mixed $question_answers
     * @return int
     */
    private function getQuestionIndexById(int $questionId, mixed $question_answers): int
    {
        for ($i = 0; $i < count($question_answers); $i++) {
            if ($question_answers[$i]['question']['question_id'] == $questionId) return $i;
        }
        return 0;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $deviceId
     * @return Response
     */
    public function destroy(int $deviceId): Response
    {
        DB::table('devices')->where('device_code_id', '=', $deviceId)->delete();
//        DB::table();
//        DB::table('personal_access_tokens')->where('device_id', '=', $userDeviceId)->delete();
        return response('destroy ' . $deviceId, 204);
    }

}
