<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ReviewApiController extends Controller
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
                'review' => [
                    'bail',
                    'json',
                ]
            ]
        );
        $review = json_decode($request->get('review'), true);
        $reviewId = DB::table('reviews')->insertGetId(
            [
                'contact_number' => $review['contact_number'],
                'comment' => $review['comment'],
                'name' => $review['name'],
                'survey_id' => $review['survey_id'],
//                'device_id' => $review['device_id'],
            ]
        );
        foreach ($review['reviewed_questions'] as $r) {
            foreach ($r['answers_id'] as $answerId) {
                DB::table('reviewed_questions')->insert([
                    'review_id' => $reviewId,
                    'question_id' => $r['question_id'],
                    'answer_id' => $answerId,
                ]);
            }
        }
        return response($reviewId, 201);
    }
}
