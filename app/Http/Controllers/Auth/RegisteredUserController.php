<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'phone' => ['required', 'regex:/^0\d{9,10}$/'],
                'password' => ['required', Rules\Password::defaults(), 'confirmed'],
                'address' => 'nullable|string|max:65535',
            ],
            [
                'name.required' => 'Tên không được để trống.',
                'name.string' => 'Tên phải là một chuỗi ký tự.',
                'name.max' => 'Tên không được vượt quá 255 ký tự.',

                'email.required' => 'Email không được để trống.',
                'email.string' => 'Email phải là một chuỗi ký tự.',
                'email.lowercase' => 'Email phải viết thường.',
                'email.email' => 'Email không hợp lệ.',
                'email.max' => 'Email không được vượt quá 255 ký tự.',
                'email.unique' => 'Email đã tồn tại.',

                'phone.required' => 'Số điện thoại không được để trống.',
                'phone.regex' => 'Số điện thoại phải có 10 đến 11 số bắt đầu bằng 0 ',

                'password.required' => 'Mật khẩu không được để trống.',
                'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'address' => $request->address,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
