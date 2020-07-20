@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Contacts</h1>
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
                            <form action="{{ route('contacts_listing') }}" method="get" class="float-right ml-2">
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
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created on</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(!empty($contacts))
                            @foreach($contacts as $key => $contact)
                                <tr>
                                    <td>{{ ( $key + 1 ) + (( $page - 1 ) * 10) }}</td>
                                    <td>{{ $contact->name }}</td>
                                    <td>{{ $contact->email }}</td>
                                    <td>{{ getExactTime($contact->created_at) }}</td>
                                    <td>
                                        <button type="button" data-name="{{ $contact->name }}" data-email="{{ $contact->email }}" data-phone="{{ $contact->phone }}" data-message="{{ $contact->message }}" data-toggle="modal" data-target="#viewContactModal" class="btn btn-primary btn-sm mr-2 view-contact-btn" >View</a>
                                        <button type="button" data-url="{{ route('contact_destroy', [$contact->id]) }}" class="btn btn-sm btn-danger delete-contact-btn" data-target="#deleteContactModal" data-toggle="modal">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="5">No contacts found</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer listing-page-footer">
                        {{ $contacts->links() }}
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<!-- Modal -->
<div class="modal fade" id="deleteContactModal" tabindex="-1" role="dialog" aria-labelledby="deleteContactModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteContactModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this contact?</p>
            </div>
            <div class="modal-footer">
                <form action="" method="post" class="delete-contact-form">
                    @csrf
                    <button type="submit" class="btn btn-danger">Yes! Delete it</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="viewContactModal" tabindex="-1" role="dialog" aria-labelledby="viewContactModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContactModalTitle">Contact Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6><strong>Name:</strong></h6>
                        <span class="contact-name"></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6><strong>Email:</strong></h6>
                        <span class="contact-email"></span>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h6><strong>Phone:</strong></h6>
                        <span class="contact-phone"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h6><strong>Message:</strong></h6>
                        <span class="contact-message"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection('content')