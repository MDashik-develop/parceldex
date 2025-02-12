@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Page Seo</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.designation.index') }}">Page Seo</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">

                    <form method="POST" action="{{ route('admin.page-seo.index') }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group">
                            <label for="page_name" class="col-sm-2 control-label">Page Name:</label>
                            <div class="col-sm-10">
                                <select name="page_name" class="form-control" required>
                                    <option>Home</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="og_title" class="col-sm-2 control-label">OG Title:</label>
                            <div class="col-sm-10">
                                <input type="text" id="og_title" name="og_title" class="form-control"
                                    value="{{ old('og_title') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="og_description" class="col-sm-2 control-label">OG Description:</label>
                            <div class="col-sm-10">
                                <textarea id="og_description" name="og_description" class="form-control" required>{{ old('og_description') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="og_image" class="col-sm-2 control-label">OG Image:</label>
                            <div class="col-sm-10">
                                <input type="file" id="og_image" name="og_image" class="form-control" required>
                                <img id="preview" src="#" alt="Preview"
                                    style="width: 100px; height: 100px; display: none;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords" class="col-sm-2 control-label">Meta Keywords:</label>
                            <div class="col-sm-10">
                                <input type="text" id="meta_keywords" name="meta_keywords" class="form-control"
                                    value="{{ old('meta_keywords') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
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
