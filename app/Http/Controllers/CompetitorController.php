<?php

namespace App\Http\Controllers;

use App\Application;
use App\Competitor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CompetitorController extends Controller
{
    public function email(Request  $request) {
        $request->validate([
            'email' => 'required'
        ]);

        $email = $request->get('email');

        try {
            $competitor = Competitor::query()
                ->where('email', $email)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'id' => $competitor->id,
            'name' => $competitor->name,
            'phone' => $competitor->phone
        ]);
    }
}
