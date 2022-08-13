<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationSkills;
use App\Job;
use App\Level;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function email(Request  $request) {
        $request->validate([
            'email' => 'required'
        ]);

        $email = $request->get('email');

        try {
            $application = Application::query()
                ->where('email', $email)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'name' => $application->name,
            'phone' => $application->phone
        ]);
    }

    public function existSkills(Request $request) {
        $request->validate([
            'email' => 'required',
            'job_id' => 'required'
        ]);

        $email = $request->get('email');
        $jobId = $request->get('job_id');

        try {
            $application = Application::query()
                ->with(['skills'])
                ->where('email', $email)
                ->where('job_id', $jobId)
                ->firstOrFail();
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'skills' => $application->skills
        ]);
    }

    public function submit(Request $request) {
        $request->validate([
            'email' => 'required|string|max:128',
            'name' => 'required|string|max:128',
            'phone' => 'required|string|max:128',
            'job_id' => 'required',
            'competences' => 'required|array'
        ]);

        $email = $request->post('email');
        $name = $request->post('name');
        $phone = $request->post('phone');
        $jobId = $request->post('job_id');
        $competences = $request->post('competences');

        $job = Job::query()
            ->with(['competences'])
            ->where('id', $jobId)
            ->first()
            ->toArray();

        if($job == null) {
            return redirect('/')->with([
                'error' => 'Internal server error'
            ]);
        }

        $existApplication = Application::query()
            ->with(['skills'])
            ->where('email', $email)
            ->where('job_id', $jobId)
            ->first();

        $availableCompetences = array_map(function($value) {
            return $value['id'];
        }, $job['competences']);

        foreach($competences as $competenceId=>$competence) {
            if(!in_array($competenceId, $availableCompetences)) {
                return redirect('/')->with([
                    'error' => 'You provided invalid competence'
                ]);
            }
        }

        if($existApplication) {
            $updatedSkills = [];

            foreach($existApplication->skills as $skill) {
                $newCompetenceValue = $competences[$skill->competence_id];

                if($newCompetenceValue != $skill->level_id) {
                    $skill->level_id = $newCompetenceValue;
                    $updatedSkills[] = $skill;
                }
            }

            foreach($updatedSkills as $skill) {
                $skill->save();
            }

            return redirect('/')->with([
                'success' => 'Your application was successfully updated'
            ]);
        }


        $application = new Application();

        $application->setAttribute('name', $name);
        $application->setAttribute('email', $email);
        $application->setAttribute('phone', $phone);
        $application->setAttribute('job_id', $jobId);

        $application->save();

        foreach($competences as $competenceId=>$competenceValue) {
            $skill = new ApplicationSkills();

            $skill->setAttribute('application_id', $application->id);
            $skill->setAttribute('level_id', $competenceValue);
            $skill->setAttribute('competence_id', $competenceId);

            $skill->save();
        }

        return redirect('/')->with([
            'success' => 'Your application was submitted'
        ]);
    }
}
