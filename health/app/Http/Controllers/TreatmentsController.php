<?php

namespace App\Http\Controllers;

use App\Role;
use App\Treatment;
use App\UserDocument;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Validator;

class TreatmentsController extends Controller
{
    /**
     * Отправка запроса
     *
     * @param Request $request
     * @return mixed
     */
    public function sendTreatment(Request $request){
        $path_photo = "";
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $path_random = Str::random(75);
            $path_photo = "uploads/".Str::substr($path_random, 0, 2);
            if (!file_exists($path_photo)){
                mkdir($path_photo);
            }
            $path_photo .= "/".Str::substr($path_random, 2, 2);
            if (!file_exists($path_photo)){
                mkdir($path_photo);
            }
            $audion_name =  Str::substr($path_random, 4).'.'.$request->photo->getClientOriginalExtension();
            $photo->move($path_photo, $audion_name);
            $path_photo .= '/'.$audion_name;
        }

        $treatment = Treatment::create([
            "user_id"=> Auth::user()->id,
            "doctor_id" => $request->doctor_id,
            "photo" => $path_photo,
            "disease" => $request->disease,
            "status" => $request->status,
            "comment" => $request->comment
        ]);


        if ($request->hasFile('documents')){
            $files = $request->file('documents');
            foreach($files as $file){
                $path_random = Str::random(75);
                $path = "uploads/".Str::substr($path_random, 0, 2);
                if (!file_exists($path)){
                    mkdir($path);
                }
                $path .= "/".Str::substr($path_random, 2, 2);
                if (!file_exists($path)){
                    mkdir($path);
                }

                $filename = Str::substr($path_random, 4).'.'.$file->getClientOriginalExtension();
                $file->move($path, $filename);
                $path.= '/'.$filename;
                UserDocument::create([
                    "treatment_id" => $treatment->id,
                    "path" => $path
                ]);
            }
        }
        return redirect()->back()->withSuccess('Данные успешно отправлены врачу');
    }

    /**
     * Create a new treatment
     *
     * @return Factory|View
     */
    public function createTreatment(){
        $role = Role::whereName('doctor')->first();
        $doctors = $role->users()->get();
        return view('user.form', [
            "doctors" => $doctors,
            "action" => route("user.send_treatment"),
            "treatment" => [],
            "is_create" => true
        ]);
    }

    /**
     * Doctor send comment
     *
     * @param Request $request
     * @return
     */
    public function sendDoctorTreatment(Request $request){
        $validator = Validator::make($request->all(), [
            'comment_doctor' => 'required'
        ]);

        if ($validator->fails()){
            return redirect()->back()->withErrors(['Заполните ответ'])->withInput();
        }

        Treatment::where("id", $request->treatment_id)
            ->update([
            "comment_doctor" => $request->comment_doctor,
        ]);
        return redirect()->back()->withSuccess('Ваш комментарий отправлен пациенту');
    }

    /**
     * Delete Treatment
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteTreatment (Request $request){
        Treatment::where("id", $request->id)->delete();
        return redirect()->back();
    }

    /**
     * Change form user treatment
     *
     * @param $id
     * @return Factory|View
     */
    public function changeTreatment ($id){
        $role = Role::whereName('doctor')->first();
        $doctors = $role->users()->get();
        $treatment = Treatment::where("id", $id)->first();
        return view('user.form', [
            "doctors" => $doctors,
            "treatment" => $treatment,
            "is_create" => false,
            "action" => route("doctor.update_treatment", ["id" => $id])
        ]);
    }

    /**
     * Update user treatment
     *
     * @param $id - id of treatment
     * @param Request $request
     * @return RedirectResponse
     */
    public function updateTreatment($id, Request $request){
        Treatment::where("id", $id)->update([
            "doctor_id" => $request->doctor_id,
            "disease" => $request->disease,
            "status" => $request->status,
            "comment" => $request->comment
        ]);
        return redirect()->back();
    }
}
