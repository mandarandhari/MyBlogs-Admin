@extends('layouts.master')

@section('content')
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
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">List</h3>

                        <div class="card-tools">                        
                            <a href="{{ url('/article/add') }}" class="btn btn-sm btn-success" style="float: left;">Add Article</a>
                            <form action="{{ route('articles_listing') }}" method="get" class="float-right ml-2">
                                <div class="input-group input-group-sm" style="width: 150px; float: right; margin-top: 0px; margin-left: 10px;">
                                    <input type="text" name="searchterm" class="form-control float-right" placeholder="Search" value="{{ Request::query('searchterm') }}">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                    </div>                            
                                </div>
                            </form>    
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <?php
                        if (Request::query('page') != "" && (int)Request::query('page') > 1) {
                            $page = (int)Request::query('page');
                        } else {
                            $page = 1;
                        }
                    ?>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Created on</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($articles))
                            @foreach($articles as $key => $article)
                                <tr>
                                    <td>{{ ( $key + 1 ) + (( $page - 1 ) * 10) }}</td>
                                    <td>
                                        <img src="{{ $article->thumb_url != '' ? $article->thumb_url : '' }}" alt="" style="width: 150px;">
                                    </td>
                                    <td>{{ $article->title }}</td>
                                    <td>{{ getExactTime($article->created_at) }}</td>
                                    <td>
                                        @if($article->comments_count > 0)
                                        <a href="{{ route('comments_listing', [$article->id]) }}" class="btn btn-primary btn-sm">Comments</a>
                                        @endif
                                        <a href="{{ route('article_edit', [$article->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <button type="button" data-url="{{ route('article_destroy', [$article->id]) }}" class="btn btn-sm btn-danger delete-article-btn" data-target="#deleteArticleModal" data-toggle="modal">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No articles found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer listing-page-footer">
                        {{ $articles->links() }}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- Modal -->
<div class="modal fade" id="deleteArticleModal" tabindex="-1" role="dialog" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArticleModalLabel">Delete Article</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this article?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" class="delete-article-form">
                    @csrf
                    <button type="submit" class="btn btn-danger submit-btn">Yes! Delete it</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection('content')