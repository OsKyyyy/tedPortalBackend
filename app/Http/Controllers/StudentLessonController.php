<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;


class StudentLessonController extends Controller
{
    public function create(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'lesson_id' => 'required|integer',
                'student_id' => 'required|integer'
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            DB::table('student_lessons')->insert([
                'lesson_id' => $request->lesson_id,
                'student_id' => $request->student_id,
                'exam_one' => $request->exam_one,
                'exam_two' => $request->exam_two,
                'exam_three' => $request->exam_three,
                'performance_one' => $request->performance_one,
                'performance_two' => $request->performance_two,
                'project' => $request->project,
                "created_at" =>  \Carbon\Carbon::now(),
            ]);

            return $this->response(true,"Kayıt başarılı bir şekilde eklendi",Response::HTTP_OK);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function update(Request $request)
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

            $result = DB::table('student_lessons')
                ->where('id', $request->id)
                ->update([
                    'exam_one' => $request->exam_one,
                    'exam_two' => $request->exam_two,
                    'exam_three' => $request->exam_three,
                    'performance_one' => $request->performance_one,
                    'performance_two' => $request->performance_two,
                    'project' => $request->project,
                    "updated_at" =>  \Carbon\Carbon::now(),
                ]);

            if($result)
            {
                return $this->response(true,"Kayıt başarılı bir şekilde güncellendi",Response::HTTP_OK);
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

            $result = DB::table('student_lessons')
                ->where('id', '=', $request->id)
                ->delete();

            if($result)
            {
                return $this->response(true,"Kayıt başarılı bir şekilde silindi",Response::HTTP_OK);
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

                $student = DB::table('student_lessons')
                    ->select(
                        'student_lessons.id',
                        'students.name as student_name',
                        'students.surname as student_surname',
                        'students.student_no',
                        'lessons.name as lesson_name',
                        'student_lessons.exam_one',
                        'student_lessons.exam_two',
                        'student_lessons.exam_three',
                        'student_lessons.performance_one',
                        'student_lessons.performance_two',
                        'student_lessons.project',
                    )
                    ->leftJoin('lessons', 'student_lessons.lesson_id', '=', 'lessons.id')
                    ->leftJoin('students', 'student_lessons.student_id', '=', 'students.id')
                    ->where('student_lessons.id', '=', $request->id)
                    ->get()
                    ->first();

                if($student === null)
                {
                    return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
                }

                return $this->response(true, [$student],Response::HTTP_OK);
            }

            $student = DB::table('student_lessons')
                ->select(
                    'student_lessons.id',
                    'students.name as student_name',
                    'students.surname as student_surname',
                    'students.student_no',
                    'lessons.name as lesson_name',
                    'student_lessons.exam_one',
                    'student_lessons.exam_two',
                    'student_lessons.exam_three',
                    'student_lessons.performance_one',
                    'student_lessons.performance_two',
                    'student_lessons.project',
                )
                ->leftJoin('lessons', 'student_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('students', 'student_lessons.student_id', '=', 'students.id')
                ->get();

            if($student === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, $student,Response::HTTP_OK);
        }
    }

    public function getByStudentId(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {

            $validator = Validator::make($request->all(),[
                'student_id' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $student = DB::table('student_lessons')
                ->select(
                    'student_lessons.id',
                    'students.name as student_name',
                    'students.surname as student_surname',
                    'students.student_no',
                    'lessons.name as lesson_name',
                    'student_lessons.exam_one',
                    'student_lessons.exam_two',
                    'student_lessons.exam_three',
                    'student_lessons.performance_one',
                    'student_lessons.performance_two',
                    'student_lessons.project',
                )
                ->leftJoin('lessons', 'student_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('students', 'student_lessons.student_id', '=', 'students.id')
                ->where('student_lessons.student_id', '=', $request->student_id)
                ->get();

            if($student === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$student],Response::HTTP_OK);
        }
    }

    public function getByLessonId(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {

            $validator = Validator::make($request->all(),[
                'lesson_id' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $student = DB::table('student_lessons')
                ->select(
                    'student_lessons.id',
                    'students.name as student_name',
                    'students.surname as student_surname',
                    'students.student_no',
                    'lessons.name as lesson_name',
                    'student_lessons.exam_one',
                    'student_lessons.exam_two',
                    'student_lessons.exam_three',
                    'student_lessons.performance_one',
                    'student_lessons.performance_two',
                    'student_lessons.project',
                )
                ->leftJoin('lessons', 'student_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('students', 'student_lessons.student_id', '=', 'students.id')
                ->where('student_lessons.lesson_id', '=', $request->lesson_id)
                ->get();

            if($student === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
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
