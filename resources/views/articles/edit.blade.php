@extends('layouts.master')

@section('content')
<script src="{{ asset('/plugins/ckeditor/ckeditor.js') }}"></script>
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Articles</h1>
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
                            <form action="{{ route('article_update', [$article->id]) }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="title">Title</label>
                                    <input type="text" id="title" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ? old('title') : $article->title }}">
                                    @error('title')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" rows="4" name="description">{{ old('description') ? old('description') : $article->description }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>                                

                                <div class="form-group">
                                    <label for="isPremium">Is premium?</label>
                                    <select class="form-control custom-select @error('isPremium') is-invalid @enderror" name="isPremium">
                                        <option selected="" disabled="">Select one</option>
                                        <option value="yes" {{ old('isPremium') == 'yes' ? 'selected' : ( $article->is_premium == 'yes' && old('isPremium') != 'no' ? 'selected' : '' ) }}>Yes</option>
                                        <option value="no" {{ old('isPremium') == 'no' ? 'selected' : ( $article->is_premium == 'no' && old('isPremium') != 'yes' ? 'selected' : '' ) }}>No</option>
                                    </select>
                                    @error('isPremium')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="content">Content</label>
                                    <textarea name="content" id="content" rows="4" class="@error('content') is-invalid @enderror">{{ old('content') ? old('content') : $article->content }}</textarea>
                                    <script>
                                        CKEDITOR.replace('content');
                                    </script>
                                    @error('content')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>                                

                                <div class="form-group">
                                    <label for="metaTitle">Meta Title</label>
                                    <input type="text" id="metaTitle" class="form-control @error('metaTitle') is-invalid @enderror" name="metaTitle" value="{{ old('metaTitle') ? old('metaTitle') : $article->meta_title }}">
                                    @error('metaTitle')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="metaDescription">Meta Description</label>
                                    <textarea name="metaDescription" id="metaDescription" rows="4" class="form-control @error('metaDescription') is-invalid @enderror">{{ old('metaDescription') ? old('metaDescription') : $article->meta_description }}</textarea>
                                    @error('metaDescription')
                                    <div class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>                                

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="banner">Banner Image</label>
                                            <input type="file" name="banner" id="banner" class="form-control @error('banner') is-invalid @enderror">
                                            @error('banner')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div id="bannerBase64" class="position-relative mt-2" style="width: 70%;">
                                                <img src="{{ $article->banner_url != '' ? $article->banner_url : '' }}" style="width: 100%;" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="thumb">Thumb Image</label>
                                            <input type="file" name="thumb" id="thumb" class="form-control @error('thumb') is-invalid @enderror">
                                            @error('thumb')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                            <div id="thumbBase64" class="position-relative mt-2" style="width: 50%;">
                                                <img src="{{ $article->thumb_url != '' ? $article->thumb_url : '' }}" style="width: 100%;" />
                                            </div>
                                        </div>
                                    </div>
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