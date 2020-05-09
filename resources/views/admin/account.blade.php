@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <h4 class="card-title">My Account</h4>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <form action="/admin/users/{{$user->id}}" method="POST" enctype="multipart/form-data" style="display:inline;">
                        {{ csrf_field() }}
                         @method('PUT')
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$user->name}}">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{$user->email}}">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Password</label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <img src="/storage/{{$user->photo_url}}" style="width:80px;">
                            <input type="file" class="form-control-file" id="file" name="file">
                        </div>
                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                        <a href="{{ URL::previous() }}" class="btn btn-dark  mr-2">Cancel</a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop