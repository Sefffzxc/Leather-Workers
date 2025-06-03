<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthAdminRequest;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $todayOrders = Order::whereDay('created_at', Carbon::today())->get();
        $yesterdayOrders = Order::whereDay('created_at', Carbon::yesterday())->get();
        $monthOrders = Order::whereMonth('created_at', Carbon::now()->month)->get();
        $yearOrders = Order::whereYear('created_at', Carbon::now()->year)->get();

        return view('admin.index')->with([
            'todayOrders' => $todayOrders,
            'yesterdayOrders' => $yesterdayOrders,
            'monthOrders' => $monthOrders,
            'yearOrders' => $yearOrders,
        ]);
    }
  
    public function login(): View|RedirectResponse
    {
        if (!auth()->guard('admin')->check()) {
            return view('admin.login');
        }

        return redirect()->route('admin.index');
    }

    public function auth(AuthAdminRequest $request): RedirectResponse|JsonResponse
    {
        if ($request->validated()) {
            if (auth()->guard('admin')->attempt([
                'email' => $request->email,
                'password' => $request->password
            ])) {
                
                // Check if request expects JSON (API request)
                if ($request->expectsJson() || $request->wantsJson()) {
                    $admin = auth()->guard('admin')->user();
                    $token = $admin->createToken('admin-api-token')->plainTextToken;
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful',
                        'token' => $token,
                        'user' => [
                            'id' => $admin->id,
                            'name' => $admin->name,
                            'email' => $admin->email,
                            'created_at' => $admin->created_at,
                            'updated_at' => $admin->updated_at
                        ]
                    ], 200);
                }

                // Web request - regenerate session and redirect
                $request->session()->regenerate();
                return redirect()->route('admin.index');
                
            } else {
                // Handle failed authentication
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid credentials'
                    ], 401);
                }
                
                return redirect()->route('admin.login')->with([
                    'error' => 'Invalid credentials'
                ]);
            }
        }
    }

    public function logout(AuthAdminRequest $request = null): RedirectResponse|JsonResponse
    {
        // Handle API logout
        if ($request && ($request->expectsJson() || $request->wantsJson())) {
            if (auth()->guard('admin')->user()) {
                auth()->guard('admin')->user()->currentAccessToken()->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully'
            ]);
        }

        // Web logout
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}