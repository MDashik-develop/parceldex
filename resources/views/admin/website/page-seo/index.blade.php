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
                        <li class="breadcrumb-item active">Page Seo</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Page List</h3>
                            <a href="{{ route('admin.page-seo.create') }}" class="btn btn-success float-right">
                                <i class="fa fa-pencil-alt"></i>Add Seo
                            </a>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>OG Title</th>
                                        <th>OG Description</th>
                                        <th>OG Image</th>
                                        <th>Meta Keywords</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($seoMetas as $k => $page_seo)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $page_seo->og_title }}</td>
                                            <td>{{ $page_seo->og_description }}</td>
                                            <td><img src="{{ asset('storage/images/' . $page_seo->og_image) }}" alt
                                                    style="width: 50px; height: 50px;"></td>
                                            <td>{{ $page_seo->meta_keywords }}</td>
                                            <td>
                                                <a href="{{ route('admin.page-seo.edit', $page_seo->id) }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
