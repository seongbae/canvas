@extends('canvas::admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Add New User</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <form action="/admin/users" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include('canvas::admin.users.form')
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="send_email" name="send_email" value="check">
                                    <label class="custom-control-label" for="send_email">Send E-mail to user</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-checkbox mr-sm-2">
                                    <input type="checkbox" class="custom-control-input" id="include_password" name="include_password" value="check">
                                    <label class="custom-control-label" for="include_password">Include password</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                            <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop