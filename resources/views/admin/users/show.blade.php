@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{$user->name}} <a href="{{ route('admin.users.edit', $user) }}"><i class="far fa-edit "></i></a></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                @foreach($user->getAttributes() as $key => $value)
                                    <tr>
                                        <th>{{$key}}:</th>
                                        <td>{{$value}}</td>
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
</div>
@stop