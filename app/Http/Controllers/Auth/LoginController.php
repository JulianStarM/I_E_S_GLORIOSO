<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditoriaLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Solo usuarios activos
        $credentials['estado'] = 1;

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Credenciales inválidas o usuario inactivo.',
            ]);
        }

        $request->session()->regenerate();

        // AUDITORÍA LOGIN (CORRECTO)
        AuditoriaLog::registrar([
            'accion' => 'LOGIN',
            'modulo' => 'usuarios',
            'registro_id' => Auth::id(),
            'datos_nuevos' => [
                'email' => Auth::user()->email,
                'nombre' => Auth::user()->nombre,
            ],
            'descripcion' => 'Inicio de sesión exitoso',
        ]);

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $email = Auth::user()?->email;

        AuditoriaLog::registrar([
            'accion' => 'LOGOUT',
            'modulo' => 'usuarios',
            'registro_id' => $userId,
            'datos_nuevos' => [
                'email' => $email,
            ],
            'descripcion' => 'Cierre de sesión',
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}