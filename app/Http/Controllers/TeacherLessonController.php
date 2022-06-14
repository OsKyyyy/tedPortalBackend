<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;


class TeacherLessonController extends Controller
{
    public function create(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'lesson_id' => 'required|integer',
                'teacher_id' => 'required|integer'
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            DB::table('teacher_lessons')->insert([
                'lesson_id' => $request->lesson_id,
                'teacher_id' => $request->teacher_id,
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
                'id' => 'required|integer',
                'lesson_id' => 'required|integer',
                'teacher_id' => 'required|integer'
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $result = DB::table('teacher_lessons')
                ->where('id', $request->id)
                ->update([
                    'lesson_id' => $request->lesson_id,
                    'teacher_id' => $request->teacher_id,
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

            $result = DB::table('teacher_lessons')
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
            $validator = Validator::make($request->all(),[
                'id' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $teacher = DB::table('teacher_lessons')
                ->select(
                    'teacher_lessons.id',
                    'lessons.name as lesson_name',
                    'users.name as teacher_name',
                    'users.surname as teacher_surname',
                    'users.phone as teacher_phone',
                    'users.email as teacher_email',
                    'teacher_lessons.created_at as created_at',
                )
                ->leftJoin('lessons', 'teacher_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'teacher_lessons.teacher_id', '=', 'users.id')
                ->where('teacher_lessons.id', '=', $request->id)
                ->get()
                ->first();

            if($teacher === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$teacher],Response::HTTP_OK);
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

            $teacher = DB::table('teacher_lessons')
                ->select(
                    'teacher_lessons.id',
                    'lessons.name as lesson_name',
                    'users.name as teacher_name',
                    'users.surname as teacher_surname',
                    'users.phone as teacher_phone',
                    'users.email as teacher_email',
                    'teacher_lessons.created_at as created_at',
                )
                ->leftJoin('lessons', 'teacher_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'teacher_lessons.teacher_id', '=', 'users.id')
                ->where('teacher_lessons.lesson_id', '=', $request->lesson_id)
                ->get();

            if($teacher === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$teacher],Response::HTTP_OK);
        }
    }

    public function getByTeacherId(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'teacher_id' => 'required|integer',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $teacher = DB::table('teacher_lessons')
                ->select(
                    'teacher_lessons.id',
                    'lessons.id as lesson_id',
                    'lessons.name as lesson_name',
                    'users.name as teacher_name',
                    'users.surname as teacher_surname',
                    'users.phone as teacher_phone',
                    'users.email as teacher_email',
                    'teacher_lessons.created_at as created_at',
                )
                ->leftJoin('lessons', 'teacher_lessons.lesson_id', '=', 'lessons.id')
                ->leftJoin('users', 'teacher_lessons.teacher_id', '=', 'users.id')
                ->where('teacher_lessons.teacher_id', '=', $request->teacher_id)
                ->get();

            if($teacher === null)
            {
                return $this->response(false, "Kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$teacher],Response::HTTP_OK);
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
