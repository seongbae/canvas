@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit User</h3>
            </div>
            <form action="/admin/users/{{$user->id}}" method="POST" enctype="multipart/form-data" style="display:inline;">
                {{ csrf_field() }}
                @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        @include('canvas::admin.users.form')
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary mr-2">Save</button>
                <a href="{{ URL::previous() }}" class="btn btn-default  mr-2">Cancel</a>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('delete-user-form').submit();return false;">Delete</button>
            </div>
            </form>
            <form action="/admin/users/{{$user->id}}" method="POST" style="display:inline;" id="delete-user-form" onsubmit="return confirm('Are you sure?');">
                {{ csrf_field() }}
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@stop