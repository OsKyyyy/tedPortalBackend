<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;


class StudentController extends Controller
{
    public function create(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'student_no' => 'required|max:255|unique:students',
                'branch_id' => 'required|max:255',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            DB::table('students')->insert([
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'email' => $request->email,
                'student_no' => $request->student_no,
                'branch_id' => $request->branch_id,
                "created_at" =>  \Carbon\Carbon::now(),
            ]);

            return $this->response(true,"Öğrenci başarılı bir şekilde eklendi",Response::HTTP_OK);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function update(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'id' => 'required|integer',
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'student_no' => 'required|max:255|unique:students,student_no,'.$request->id.'',
                'branch_id' => 'required|max:255',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $result = DB::table('students')
                ->where('id', $request->id)
                ->update([
                    'name' => $request->name,
                    'surname' => $request->surname,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'student_no' => $request->student_no,
                    'branch_id' => $request->branch_id,
                    "updated_at" =>  \Carbon\Carbon::now(),
                ]);

            if($result)
            {
                return $this->response(true,"Öğrenci başarılı bir şekilde güncellendi",Response::HTTP_OK);
            }

            return $this->response(false,"Güncellenmek istenen kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function delete(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'id' => 'required|integer'
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $result = DB::table('students')
                ->where('id', '=', $request->id)
                ->delete();

            if($result)
            {
                return $this->response(true,"Öğrenci başarılı bir şekilde silindi",Response::HTTP_OK);
            }

            return $this->response(false,"Silinmek istenen kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function get(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            if($request->all())
            {
                $validator = Validator::make($request->all(),[
                    'id' => 'required|integer',
                ]);

                if($validator->fails())
                {
                    return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
                }

                $student = DB::table('students')
                    ->select('students.id', 'students.name', 'students.surname', 'students.phone','students.email','students.student_no','branches.id as branch_id','branches.name as branch','branches.code as code','students.created_at as created_at','branches.updated_at as updated_at')
                    ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
                    ->where('students.id', '=', $request->id)
                    ->get()->first();

                if($student === null)
                {
                    return $this->response(false, "Öğrenci bulunamadı",Response::HTTP_BAD_REQUEST);
                }

                return $this->response(true, [$student],Response::HTTP_OK);
            }

            $student = DB::table('students')
                ->select('students.id', 'students.name', 'students.surname', 'students.phone','students.email','students.student_no','branches.id as branch_id','branches.name as branch','branches.code as code','students.created_at as created_at','branches.updated_at as updated_at')
                ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
                ->get();

            if($student === null)
            {
                return $this->response(false, "Öğrenci bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, $student,Response::HTTP_OK);
        }
    }

    public function getByStudentNo(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'student_no' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $student = DB::table('students')
                ->select('students.id', 'students.name', 'students.surname', 'students.phone','students.email','students.student_no','branches.name as branch','branches.code as code','students.created_at as created_at','branches.updated_at as updated_at')
                ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
                ->where('students.student_no', '=', $request->student_no)
                ->get();

            if($student === null)
            {
                return $this->response(false, "Öğrenci bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$student],Response::HTTP_OK);
        }
    }

    public function getByBranchId(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'branch_id' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $student = DB::table('students')
                ->select('students.id', 'students.name', 'students.surname', 'students.phone','students.email','students.student_no','branches.name as branch','branches.code as code','students.created_at as created_at','branches.updated_at as updated_at')
                ->leftJoin('branches', 'students.branch_id', '=', 'branches.id')
                ->where('students.branch_id', '=', $request->branch_id)
                ->get();

            if($student === null)
            {
                return $this->response(false, "Öğrenci bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$student],Response::HTTP_OK);
        }
    }

    public function response($status,$message,$responseCode)
    {
        return response()->json([
            "status" => $status,
            "message" => $message
        ],$responseCode);
    }

}
