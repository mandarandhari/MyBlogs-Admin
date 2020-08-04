@extends('layouts.master')

@section('content')
<script src="{{ asset('/plugins/ckeditor/ckeditor.js') }}"></script>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Users</h1>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <!-- /.card-header -->
        <div class="card-body table-responsive p-0">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('user_update', [$user->id]) }}" method="post">
                                @csrf

                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ? old('name') : $user->name }}">
                                    @error('name')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ? old('email') : $user->email }}" disabled>
                                    @error('email')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>                                

                                <div class="form-group">
                                    <label for="type">Type</label>
                                    <select class="form-control custom-select @error('type') is-invalid @enderror" name="type">
                                        <option selected="" disabled="">Select one</option>
                                        <option value="admin" {{ old('type') == 'admin' ? 'selected' : ( $user->type == 'admin' && old('type') != 'author' ? 'selected' : '' ) }}>Admin</option>
                                        <option value="author" {{ old('type') == 'author' ? 'selected' : ( $user->type == 'author' && old('type') != 'admin' ? 'selected' : '' ) }}>Author</option>
                                    </select>
                                    @error('type')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>                               

                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password">
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="confirmPassword">Confirm Password</label>
                                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control @error('confirmPassword') is-invalid @enderror">
                                    @error('confirmPassword')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>    

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-md float-right submit-btn">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
@endsection('content')