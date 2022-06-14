<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function create(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:255',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            DB::table('branches')->insert([
                'name' => $request->name,
                'code' => $request->code,
                "created_at" =>  \Carbon\Carbon::now(),
            ]);

            return $this->response(true,"Şube başarılı bir şekilde eklendi",Response::HTTP_OK);
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
                'code' => 'required|string|max:255',
            ]);

            if($validator->fails())
            {
                return $this->response(false,$validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $result = DB::table('branches')
                ->where('id', $request->id)
                ->update([
                    'name' => $request->name,
                    'code' =>$request->code,
                    'updated_at' => \Carbon\Carbon::now()
                ]);

            if($result)
            {
                return $this->response(true,"Şube başarılı bir şekilde güncellendi",Response::HTTP_OK);
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

            $result = DB::table('branches')
                ->where('id', '=', $request->id)
                ->delete();

            if($result)
            {
                return $this->response(true,"Şube başarılı bir şekilde silindi",Response::HTTP_OK);
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

                $branch = DB::table('branches')
                    ->where('id', '=', $request->id)
                    ->get()->first();

                if($branch === null)
                {
                    return $this->response(false, "Şube bulunamadı",Response::HTTP_BAD_REQUEST);
                }

                return $this->response(true, [$branch],Response::HTTP_OK);
            }

            $branch = DB::table('branches')->get();

            if($branch === null)
            {
                return $this->response(false, "Şube bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, $branch,Response::HTTP_OK);
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
