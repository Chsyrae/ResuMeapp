<?php

namespace App\Http\Controllers;

use App\Models\EducationExperience;
use App\Models\Interest;
use App\Models\PersonalDetails;
use App\Models\PersonalInfo;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\WorkExperience;
use App\Services\AgentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AgentService $agentService)
    {   
        if($request->query('type') == 'getAdvice') {
            $userSkills     = implode(',', $request->skills );
            $careerAdvisory = $agentService->createRequest($userSkills);
            \Log::info($careerAdvisory);
            return response()->json($careerAdvisory);
        } else {
            $personalInfo         = $request['personalInfo'];
            $workExperiences      = $request['workExperience'];
            $educationExperiences = $request['educationExperience'];
            $skills               = $request['skills'];
            $interests            = $request['interests'];
            $templateName         = $request['templateName'];

            try{
                $resumeRecord           = new Resume;
                $resumeRecord->user     = auth()->id();
                $resumeRecord->template = $templateName;
                $resumeRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            try{
                $peronalInfoRecord                     = new PersonalDetails;
                $peronalInfoRecord->resume             = $resumeRecord->id;
                $peronalInfoRecord->first_name         = $personalInfo['fname'];
                $peronalInfoRecord->last_name          = $personalInfo['lname'];
                $peronalInfoRecord->email              = $personalInfo['email'];
                $peronalInfoRecord->physical_address   = $personalInfo['physicalAddress'];
                $peronalInfoRecord->phone_number       = $personalInfo['phoneNo'];
                $peronalInfoRecord->personal_statement = $personalInfo['personalStatement'];
                $peronalInfoRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            try{
                $workExperienceRecord = new WorkExperience;
                foreach($workExperiences as $workExperience) {
                    $workExperienceRecord->resume       = $resumeRecord->id;
                    $workExperienceRecord->organization = $workExperience['organization'];
                    $workExperienceRecord->designation  = $workExperience['designation'];
                    $workExperienceRecord->date_joined  = Carbon::createFromFormat('d/m/Y', $workExperience['dateJoined']);
                    $workExperienceRecord->date_left    = $workExperience['dateLeft'] ? Carbon::createFromFormat('d/m/Y', $workExperience['dateLeft']): null;
                    $workExperienceRecord->achievements = json_encode($workExperience['achievementsResponsibilities']);
                }
                $workExperienceRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            try{
                $educationExperienceRecord = new EducationExperience; 
                foreach($educationExperiences as $educationExperience) {
                    $educationExperienceRecord->resume        = $resumeRecord->id;
                    $educationExperienceRecord->institution   = $educationExperience['institution'];
                    $educationExperienceRecord->date_joined   = Carbon::createFromFormat('d/m/Y', $educationExperience['dateJoined']);
                    $educationExperienceRecord->date_left     = $educationExperience['dateLeft'] ? Carbon::createFromFormat('d/m/Y', $educationExperience['dateLeft']) : null;
                    $educationExperienceRecord->qualification = $educationExperience['qualification'];
                }
                $educationExperienceRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            try{
                $skillRecord = new Skill;
                $skillRecord->resume = $resumeRecord->id;
                $skillRecord->skills = json_encode($skills);
                $skillRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }

            try{
                $interestRecord            = new Interest;
                $interestRecord->resume    = $resumeRecord->id;
                $interestRecord->interests = json_encode($interests);
                $interestRecord->save();
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }


            $pdf      = \Pdf::loadView("templates.$templateName", compact('personalInfo', 'workExperiences', 'educationExperiences', 'skills', 'interests'));
            $fileName = $personalInfo['fname'] . ' ' . $personalInfo['lname'];
            return response($pdf->stream(), 200, [
                'Content-Disposition' => "attachment; filename={$fileName}"
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
