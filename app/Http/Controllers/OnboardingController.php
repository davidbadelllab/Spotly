<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Http\Requests\OnboardingRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        // If user has completed onboarding, redirect to dashboard
        if ($user->company && $user->company->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        // Get or initialize company
        $company = $user->company ?? new Company();

        return Inertia::render('Onboarding/Index', [
            'company' => $company,
            'venueTypes' => [
                'hotel' => [
                    'name' => 'Hotel',
                    'description' => 'Gestiona habitaciones y servicios hoteleros',
                    'icon' => 'hotel',
                    'benefits' => [
                        'Sistema de reservas de habitaciones',
                        'Control de servicios y amenidades',
                        'Gestión de eventos y actividades'
                    ]
                ],
                'sports' => [
                    'name' => 'Centro Deportivo',
                    'description' => 'Administra instalaciones y canchas deportivas',
                    'icon' => 'sports',
                    'benefits' => [
                        'Reservas de canchas e instalaciones',
                        'Gestión de membresías',
                        'Programación de eventos deportivos'
                    ]
                ],
                'restaurant' => [
                    'name' => 'Restaurante',
                    'description' => 'Gestiona mesas y reservaciones',
                    'icon' => 'restaurant',
                    'benefits' => [
                        'Sistema de reservas de mesas',
                        'Gestión de menú y carta',
                        'Eventos especiales y catering'
                    ]
                ],
                'coworking' => [
                    'name' => 'Espacio Coworking',
                    'description' => 'Administra espacios de trabajo y salas de reuniones',
                    'icon' => 'coworking',
                    'benefits' => [
                        'Reservas de espacios de trabajo',
                        'Gestión de salas de reuniones',
                        'Control de membresías y accesos'
                    ]
                ],
            ],
        ]);
    }

    public function store(OnboardingRequest $request): RedirectResponse
    {
        try {
            Log::info('Onboarding store request data:', $request->validated());
            
            $user = Auth::user();
            $company = $user->company ?? new Company();
            
            // Create or update company
            $company->fill([
                ...$request->validated(),
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
            
            $company->save();
            $company->markOnboardingComplete();

            Log::info('Company profile completed successfully', [
                'company_id' => $company->id,
                'user_id' => $user->id
            ]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Tu perfil de empresa ha sido completado exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error completing company profile:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? null
            ]);

            return back()
                ->withInput()
                ->with('error', 'Hubo un error al guardar tu perfil. Por favor intenta nuevamente.');
        }
    }

    public function skip()
    {
        $user = Auth::user();
        
        // Create a pending company if user doesn't have one
        if (!$user->company) {
            $company = new Company([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
            $company->save();
        }

        return redirect()->route('dashboard')
            ->with('warning', 'Puedes completar tu perfil de empresa más tarde en la configuración.');
    }
}
