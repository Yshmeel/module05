<?php

namespace App\Http\Controllers;

use App\Competences;
use App\Job;
use App\Level;
use Illuminate\Http\Request;

class JoblistController extends Controller
{
    public function index() {
        $jobs = Job::with(['competences', 'applications', 'applications.skills', 'applications.skills.competence', 'applications.skills.level'])
            ->get()
            ->toArray();

        foreach($jobs as $jobId=>$job) {
            foreach($job['applications'] as $key=>$application) {
                $totalWeight = 0;

                foreach($application['skills'] as $skill) {
                    $totalWeight += $skill['competence']['height']*$skill['level']['factor'];
                }

                $jobs[$jobId]['applications'][$key]['weight'] = $totalWeight;
            }

            usort($jobs[$jobId]['applications'], function($a, $b) {
                return $b['weight'] - $a['weight'];
            });
        }

        return view('joblist', [
            'jobs' => $jobs
        ]);
    }

    public function newJob(Request $request) {
        if($request->method() === 'POST') {
            $request->validate([
                'name' => 'required|string|max:128',
                'competences' => 'required'
            ]);

            $name = $request->post('name');
            $competences = $request->post('competences');

            if(count($competences) == 0) {
                return view('newjob')->with([
                    'error' => 'Provide any competence for new job'
                ]);
            }

            $job = new Job();
            $job->setAttribute('job', $name);
            $job->save();

            $totalWeight = 0;

            $createdCompetences = [];

            foreach($competences['name'] as $key=>$name) {
                $weight = $competences['weight'][$key] ?? '';

                if(empty($name) || empty($weight)) {
                    continue;
                }

                $totalWeight += $weight;

                $competence = new Competences();
                $competence->competence = $name;
                $competence->height = $weight;
                $competence->job_id = $job->id;

                $createdCompetences[] = $competence;
            }

            if($totalWeight != 100) {
                return view('newjob')->with([
                    'error' => 'Total weight of all competences must equal 100'
                ]);
            }

            foreach($createdCompetences as $competence) {
                $competence->save();
            }

            return redirect('/joblist')->with([
                'success' => 'New job was successfully created'
            ]);
        }

        return view('newjob');
    }
}
