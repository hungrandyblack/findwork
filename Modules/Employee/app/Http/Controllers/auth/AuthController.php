<?php

namespace Modules\Employee\app\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Modules\Employee\app\Http\Requests\StoreLoginRequest;
use Modules\Employee\app\Http\Requests\StoreRegisterRequest;
use Modules\Auth\app\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\app\Http\Requests\ResetPasswordRequest;
use Modules\Auth\app\Models\PasswordResetToken;
use Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Employee\app\Models\UserEmployee;


class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('employee.profile.index');
        } else {
            return view('employee::auth.login');
        }
    }

    public function postLogin(StoreLoginRequest $request)
    {
        $dataUser = $request->only('email', 'password');
        if (Auth::attempt($dataUser, $request->remember)) {
            return redirect()->route('employee.profile.index'); 
        } else {
            return redirect()->route('employee.auth.login')->with('error', 'Account or password is incorrect');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('employee.auth.login');
    }
    public function register(){
            return view('employee::auth.register');
    }
    public function postRegister(StoreRegisterRequest $request)
    {
        // dd($request);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            // Thêm thao tác khác trong giao dịch
            $user_employees = new UserEmployee();
            $user_employees->user_id = $user->id;
            $user_employees->company_name = $request->company_name;
            $user_employees->company_phone = $request->company_phone;
            $user_employees->company_address = $request->company_address;
            $user_employees->company_website = $request->company_website;
            $user_employees->save();

            DB::commit(); // Hoàn thành giao dịch

            $message = "Đăng ký thành công!";
            return redirect()->route('employee.auth.login')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback(); // Hoàn tác giao dịch nếu có lỗi
            Log::error('Bug occurred: ' . $e->getMessage());
            return view('employee::auth.register')->with('error', 'Đăng ký bị lỗi!');
        }
    }
}