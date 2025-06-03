@extends('admin.layouts.app')

@section('title')
    Update Your Info
@endsection
 
@section('content')
    <div class="row">
        @include('admin.layouts.sidebar')
        <div class="col-md-9">
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mt-2">Update Your Information</h3>
                    </div>
                    <hr>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card-body">
                        <form action="{{ route('admin.info.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Profile Image Section -->
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">Profile Image</label>
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="position-relative">
                                            <img id="imagePreview" 
                                                src="{{ $adminInfo->image ? asset('storage/' . $adminInfo->image) : asset('images/default-avatar.png') }}" 
                                                alt="Profile Image" 
                                                class="rounded-circle border" 
                                                style="width: 120px; height: 120px; object-fit: cover;">
                                            
                                            @if($adminInfo->hasImage())
                                                <button type="button" id="removeImageBtn" 
                                                        class="btn btn-sm btn-danger position-absolute" 
                                                        style="top: -5px; right: -5px; border-radius: 50%; width: 30px; height: 30px; padding: 0;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*">
                                            <div class="form-text">
                                                Accepted formats: JPEG, PNG, JPG, GIF. Maximum size: 2MB
                                            </div>
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $adminInfo->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="available" {{ old('status', $adminInfo->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="busy" {{ old('status', $adminInfo->status) == 'busy' ? 'selected' : '' }}>Busy</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Skills</label>
                                    <div class="row">
                                        @php
                                            $availableSkills = ['Hand Stitching', 'Cutting and Pattern Making', 'Edge Finishing', 'Tooling and Carving', 'Dyeing and Finishing', 'Skiving', 'Hardware Installation', 'Molding and Wet Forming'];
                                            $currentSkills = old('skills', $adminInfo->skills ?? []);
                                        @endphp
                                        
                                        @foreach($availableSkills as $skill)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="skills[]" 
                                                           value="{{ $skill }}" id="skill_{{ $loop->index }}"
                                                           {{ in_array($skill, $currentSkills) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="skill_{{ $loop->index }}">
                                                        {{ $skill }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="skill_level" class="form-label">Skill Level</label>
                                    <select class="form-select @error('skill_level') is-invalid @enderror" id="skill_level" name="skill_level" required>
                                        <option value="beginner" {{ old('skill_level', $adminInfo->skill_level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                        <option value="intermediate" {{ old('skill_level', $adminInfo->skill_level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                        <option value="advanced" {{ old('skill_level', $adminInfo->skill_level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                        <option value="expert" {{ old('skill_level', $adminInfo->skill_level) == 'expert' ? 'selected' : '' }}>Expert</option>
                                    </select>
                                    @error('skill_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="signature_products" class="form-label">Signature Products</label>
                                    <textarea class="form-control @error('signature_products') is-invalid @enderror" 
                                              id="signature_products" name="signature_products" rows="4" 
                                              placeholder="Describe your signature products or specialties...">{{ old('signature_products', $adminInfo->signature_products) }}</textarea>
                                    @error('signature_products')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Update Information
                                    </button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-secondary ms-2">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const removeImageBtn = document.getElementById('removeImageBtn');

    // Preview image when file is selected
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                // Show remove button if it was hidden
                if (removeImageBtn) {
                    removeImageBtn.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    if (removeImageBtn) {
        removeImageBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove this image?')) {
                fetch('{{ route("admin.info.remove-image") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        imagePreview.src = '{{ asset("images/default-avatar.png") }}';
                        removeImageBtn.style.display = 'none';
                        imageInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to remove image. Please try again.');
                });
            }
        });
    }
});
</script>
@endpush