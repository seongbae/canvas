@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    Hello, {{Auth::user()->name}}.<br>
                </div>
            </div>
        </div>
    </div>
</div>
@stop