@extends('layouts.admin_layout.admin_layout')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            <h1 class="m-0 text-dark">Thana/Upazila</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.upazila.index') }}">Thana/Upazilas</a></li>
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
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Edit Thana/Upazila </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="col-md-offset-1 col-md-10 ">
                                <div class="card card-primary">
                                    <form role="form" action="{{ route('admin.upazila.update', $upazila->id) }}" method="POST"
                                        enctype="multipart/form-data" onsubmit="return editForm()">
                                        @csrf
                                        @method('patch')
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="district_id"> Districts </label>
                                                <select name="district_id" id="district_id" class="form-control select2" style="width: 100%">
                                                  <option value="0">Select District</option>
                                                  @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                  @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{{ $upazila->name ?? old('name') }}" class="form-control" placeholder="Thana/Upazila Name" required>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style_css')
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endpush

@push('script_js')
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $("#district_id").val('{{ $upazila->district_id }}');

        function editForm() {
            let type = $('#district_id').val();
            if(type == '0'){
                toastr.error("Please Select District..");
                return false;
            }
        }

    </script>

@endpush
