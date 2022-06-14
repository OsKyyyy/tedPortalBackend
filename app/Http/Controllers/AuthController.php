<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails())
        {
            return $this->response(false, $validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        isset($request->type) == true ? $type = $request->type : $type = 1;

        User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => $type,
            'password' => Hash::make($request->password)
        ]);

        return $this->response(true, "Kullanıcı başarılı bir şekilde eklendi",Response::HTTP_OK);

    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return $this->response(false, "Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        //dd($user);
        $token = $user->createToken('token')->plainTextToken;

        return $this->response(true, [ "token" => $token, "user" => $user],Response::HTTP_OK);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return $this->response(true, "Oturum başarılı bir şekilde kapatıldı",Response::HTTP_OK);
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
                    return $this->response(false, $validator->errors(),Response::HTTP_BAD_REQUEST);
                }

                $user = DB::table('users')
                    ->select('id','name','surname','phone','email')
                    ->where('id', '=', $request->id)
                    ->get()->first();

                if($user === null)
                {
                    return $this->response(false, "Kullanıcı bulunamadı",Response::HTTP_BAD_REQUEST);
                }

                return $this->response(true, $user,Response::HTTP_OK);
            }

            $user = DB::table('users')
                ->select('id','name','surname','phone','email')
                ->where('type', '=', 1)
                ->get();

            if($user === null)
            {
                return $this->response(false, "Kullanıcı bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, $user,Response::HTTP_OK);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function getByEmail(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $validator = Validator::make($request->all(),[
                'email' => 'required|string',
            ]);

            if($validator->fails())
            {
                return $this->response(false, $validator->errors(),Response::HTTP_BAD_REQUEST);
            }

            $user = DB::table('users')
                ->select('id','name','surname','phone','email')
                ->where('email', '=', $request->email)
                ->get()->first();

            if($user === null)
            {
                return $this->response(false, "Kullanıcı bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$user],Response::HTTP_OK);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function getByTeacher(Request $request)
    {
        $checkUserAuth = Auth::check();

        if($checkUserAuth)
        {
            $user = DB::table('users')
                ->select('id','name','surname','phone','email')
                ->where('type', '=', 1)
                ->get();

            if($user === null)
            {
                return $this->response(false, "Kullanıcı bulunamadı",Response::HTTP_BAD_REQUEST);
            }

            return $this->response(true, [$user],Response::HTTP_OK);
        }

        return $this->response(false,"Giriş bilgilerinizi kontrol ediniz",Response::HTTP_UNAUTHORIZED);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$request->id.'',
        ]);

        if($validator->fails())
        {
            return $this->response(false, $validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        $result = DB::table('users')
            ->where('id', $request->id)
            ->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                "updated_at" =>  \Carbon\Carbon::now(),
            ]);

        if($result)
        {
            return $this->response(true,"Kayıt başarılı bir şekilde güncellendi",Response::HTTP_OK);
        }

        return $this->response(false,"Güncellenmek istenen kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|integer',
        ]);

        if($validator->fails())
        {
            return $this->response(false, $validator->errors(),Response::HTTP_BAD_REQUEST);
        }

        $result = DB::table('users')->where('id', '=', $request->id)->delete();

        if($result)
        {
            return $this->response(true,"Kayıt başarılı bir şekilde silindi",Response::HTTP_OK);
        }

        return $this->response(false,"Silinmek istenen kayıt bulunamadı",Response::HTTP_BAD_REQUEST);
    }

    public function response($status,$message,$responseCode)
    {
        return response()->json([
            "status" => $status,
            "message" => $message
        ],$responseCode);
    }
}
