<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class AdminInfoController extends Controller
{
    public function index()
    {
        // Get the currently authenticated admin
        $admin = auth()->guard('admin')->user() ?? auth()->user();
        $adminInfo = $admin->adminInfo;
        
        // If admin info doesn't exist, create a default one
        if (!$adminInfo) {
            $adminInfo = AdminInfo::create([
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'status' => 'available',
                'skills' => [],
                'skill_level' => 'beginner',
                'signature_products' => '',
                'image' => null,
            ]);
        }

        return view('admin.info.index', compact('adminInfo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:busy,available',
            'skills' => 'array',
            'skill_level' => 'required|in:beginner,intermediate,advanced,expert',
            'signature_products' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $admin = auth()->guard('admin')->user() ?? auth()->user();
        $adminInfo = $admin->adminInfo;
        
        if (!$adminInfo) {
            $adminInfo = new AdminInfo();
            $adminInfo->admin_id = $admin->id;
        }

        // Handle image upload
        $imagePath = $adminInfo->image; // Keep existing image by default
        
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($adminInfo->image && Storage::disk('public')->exists($adminInfo->image)) {
                Storage::disk('public')->delete($adminInfo->image);
            }
            
            // Store new image
            $imagePath = $request->file('image')->store('admin-profiles', 'public');
        }

        $adminInfo->fill([
            'name' => $request->name,
            'status' => $request->status,
            'skills' => $request->skills ?? [],
            'skill_level' => $request->skill_level,
            'signature_products' => $request->signature_products,
            'image' => $imagePath,
        ]);
        
        $adminInfo->save();

        return redirect()->route('admin.info.index')->with('success', 'Information updated successfully!');
    }

    public function removeImage()
    {
        $admin = auth()->guard('admin')->user() ?? auth()->user();
        $adminInfo = $admin->adminInfo;
        
        if ($adminInfo && $adminInfo->image) {
            // Delete the image file
            if (Storage::disk('public')->exists($adminInfo->image)) {
                Storage::disk('public')->delete($adminInfo->image);
            }
            
            // Remove image path from database
            $adminInfo->image = null;
            $adminInfo->save();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false]);
    }
}