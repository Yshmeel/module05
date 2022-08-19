<?php

namespace App\Http\Controllers;

use App\Application;
use App\ApplicationSkills;
use App\Competitor;
use App\Job;
use App\Level;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function existSkills(Request $request) {
        $request->validate([
            'competitor_id' => 'required',
            'job_id' => 'required'
        ]);

        $competitorId = $request->get('competitor_id');
        $jobId = $request->get('job_id');

        try {
            $application = Application::query()
                ->with(['skills'])
                ->where('competitor_id', $competitorId)
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

        // @todo Add transactions here

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

        $competitor = Competitor::query()
            ->where('email', $email)
            ->first();

        // If competitor does not exists, we need to create him later, using passed values
        // Otherwise we need to request if application is alreaddy exists for $competitor->id
        if($competitor == null) {
            $existApplication = null;
        } else {
            $existApplication = Application::query()
                ->with(['skills'])
                ->where('competitor_id', $competitor->id)
                ->where('job_id', $jobId)
                ->first();
        }

        $availableCompetences = array_map(function($value) {
            return $value['id'];
        }, $job['competences']);

        // If any of competence skills is not available in job, output an error
        foreach($competences as $competenceId=>$competence) {
            if(!in_array($competenceId, $availableCompetences)) {
                return redirect('/')->with([
                    'error' => 'You provided invalid competence'
                ]);
            }
        }

        // If application exists, we need only to update his skills from passed values
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

        // Creating new competitor
        if(!$competitor) {
            $competitor = new Competitor();

            $competitor->setAttribute('name', $name);
            $competitor->setAttribute('email', $email);
            $competitor->setAttribute('phone', $phone);

            $competitor->save();
        }

        // Now we can create a new application to database
        $application = new Application();

        $application->setAttribute('competitor_id', $competitor->id);
        $application->setAttribute('job_id', $jobId);

        $application->save();

        // Append skills by competence in application
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
