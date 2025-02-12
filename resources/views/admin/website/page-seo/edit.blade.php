@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Designation</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.designation.index') }}">Designations</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <!-- Edit Form -->
                    <form action="{{ route('admin.page-seo.update', $seoMeta->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Page Name -->
                        <div class="form-group">
                            <label for="page_name">Page Name</label>
                            <input type="text" name="page_name" value="{{ $seoMeta->page_name }}" class="form-control"
                                required>
                        </div>

                        <!-- OG Title -->
                        <div class="form-group">
                            <label for="og_title">OG Title</label>
                            <input type="text" name="og_title" value="{{ $seoMeta->og_title }}" class="form-control"
                                required>
                        </div>

                        <!-- OG Description -->
                        <div class="form-group">
                            <label for="og_description">OG Description</label>
                            <textarea name="og_description" class="form-control" required>{{ $seoMeta->og_description }}</textarea>
                        </div>

                        <!-- OG Image -->
                        <div class="form-group">
                            <label for="og_image">OG Image</label>
                            <input type="file" name="og_image" class="form-control">
                            <img src="{{ asset('storage/images/' . $seoMeta->og_image) }}" alt="OG Image" width="100">
                        </div>

                        <!-- Meta Keywords -->
                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" name="meta_keywords" value="{{ $seoMeta->meta_keywords }}"
                                class="form-control" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_js')
    <script>
        $(document).ready(function() {
            $('#og_image').on('change', function() {
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                    $('#preview').show();
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
@endpush
