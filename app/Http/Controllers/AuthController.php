<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaLog;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de login.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Procesa el login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Ingrese un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        // Verificar que el usuario existe y está activo
        $usuario = Usuario::where('correo', $credentials['correo'])->first();

        if ($usuario && ! $usuario->estaActivo()) {
            return back()->withErrors([
                'correo' => 'Su cuenta se encuentra desactivada. Contacte al administrador.',
            ])->withInput($request->only('correo'));
        }

        if (Auth::attempt(['correo' => $credentials['correo'], 'password' => $credentials['password']], $request->boolean('recordar'))) {
            $request->session()->regenerate();

            // Actualizar último acceso
            $usuario = Auth::user();
            $usuario->update(['ultimo_acceso' => now()]);

            // Registrar auditoría
            AuditoriaLog::registrar([
                'accion' => 'login',
                'modulo' => 'autenticacion',
                'descripcion' => "Inicio de sesión exitoso desde IP: {$request->ip()}",
            ]);

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->withInput($request->only('correo'));
    }

    /**
     * Cierra la sesión.
     */
    public function logout(Request $request): RedirectResponse
    {
        AuditoriaLog::registrar([
            'accion' => 'logout',
            'modulo' => 'autenticacion',
            'descripcion' => 'Cierre de sesión',
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
