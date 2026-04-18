<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MemberAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.member-register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_MEMBER,
            'member_code' => $this->nextMemberCode(),
        ]);

        Auth::login($user);

        return redirect()->intended(route('member.profile'))->with('success', 'Berhasil daftar member. Selamat datang!');
    }

    public function showLogin()
    {
        return view('auth.member-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        $intended = (string) $request->session()->get('url.intended', '');
        $intendedPath = $intended ? parse_url($intended, PHP_URL_PATH) : null;

        if ($user->isAdmin()) {
            if (is_string($intendedPath) && str_starts_with($intendedPath, '/admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }

            $request->session()->forget('url.intended');

            return redirect()->route('admin.dashboard');
        }

        // Member only.
        if (is_string($intendedPath) && str_starts_with($intendedPath, '/admin')) {
            $request->session()->forget('url.intended');
        }

        return redirect()->intended(route('member.profile'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Logout berhasil.');
    }

    private function nextMemberCode(): string
    {
        $last = User::query()
            ->whereNotNull('member_code')
            ->orderByDesc('id')
            ->value('member_code');

        $num = 0;

        if (is_string($last) && preg_match('/WLC-(\d{4})/', $last, $m)) {
            $num = (int) $m[1];
        }

        return sprintf('WLC-%04d', $num + 1);
    }
}
