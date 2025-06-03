<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AdminInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminInfoApiController extends Controller
{
    /**
     * Get all admin information for public display
     */
    public function index()
    {
        try {
            Log::info('AdminInfoApiController@index called');
            
            $admins = AdminInfo::select([
                'id',
                'admin_id',
                'name',
                'status',
                'skills',
                'skill_level',
                'signature_products',
                'image',
                'created_at',
                'updated_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

            Log::info('Found admins count: ' . $admins->count());

            // Transform the data to ensure skills is always an array
            $transformedAdmins = $admins->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'admin_id' => $admin->admin_id,
                    'name' => $admin->name,
                    'status' => $admin->status,
                    'skills' => is_array($admin->skills) ? $admin->skills : json_decode($admin->skills ?? '[]', true),
                    'skill_level' => $admin->skill_level,
                    'signature_products' => $admin->signature_products,
                    'image' => $admin->image,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedAdmins,
                'message' => 'Admins retrieved successfully',
                'count' => $transformedAdmins->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('AdminInfoApiController@index error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admins',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get admin information by ID
     */
    public function show($id)
    {
        try {
            Log::info("AdminInfoApiController@show called with ID: $id");
            
            $admin = AdminInfo::select([
                'id',
                'admin_id',
                'name',
                'status',
                'skills',
                'skill_level',
                'signature_products',
                'image',
                'created_at',
                'updated_at'
            ]) 
            ->findOrFail($id);

            // Transform the data
            $transformedAdmin = [
                'id' => $admin->id,
                'admin_id' => $admin->admin_id,
                'name' => $admin->name,
                'status' => $admin->status,
                'skills' => is_array($admin->skills) ? $admin->skills : json_decode($admin->skills ?? '[]', true),
                'skill_level' => $admin->skill_level,
                'signature_products' => $admin->signature_products,
                'image' => $admin->image,
                'created_at' => $admin->created_at,
                'updated_at' => $admin->updated_at,
            ];

            return response()->json([
                'success' => true,
                'data' => $transformedAdmin,
                'message' => 'Admin retrieved successfully'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found',
                'error' => 'Admin with the specified ID does not exist'
            ], 404);
        } catch (\Exception $e) {
            Log::error("AdminInfoApiController@show error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admin',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get only available admins
     */
    public function available()
    {
        try {
            Log::info('AdminInfoApiController@available called');
            
            $admins = AdminInfo::where('status', 'available')
                ->select([
                    'id',
                    'admin_id',
                    'name',
                    'status',
                    'skills',
                    'skill_level',
                    'signature_products',
                    'image',
                    'created_at',
                    'updated_at'
                ])
                ->orderBy('created_at', 'desc')
                ->get();

            Log::info('Found available admins count: ' . $admins->count());

            // Transform the data
            $transformedAdmins = $admins->map(function ($admin) {
                return [
                    'id' => $admin->id,
                    'admin_id' => $admin->admin_id,
                    'name' => $admin->name,
                    'status' => $admin->status,
                    'skills' => is_array($admin->skills) ? $admin->skills : json_decode($admin->skills ?? '[]', true),
                    'skill_level' => $admin->skill_level,
                    'signature_products' => $admin->signature_products,
                    'image' => $admin->image,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedAdmins,
                'message' => 'Available admins retrieved successfully',
                'count' => $transformedAdmins->count()
            ], 200);

        } catch (\Exception $e) {
            Log::error('AdminInfoApiController@available error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve available admins',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}