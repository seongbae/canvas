<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{ isset($user) ? $user->name : old('name')}}">
</div>
<div class="form-group">
    <label for="email">Email address</label>
    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{ isset($user) ? $user->email : old('email')}}">
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
    @if (isset($user))
    <img src="{{ $user->{config('canvas.user_image_field')} }}" style="width:80px;" class="img-circle elevation-2">
    @endif
    <input type="file" class="form-control-file" id="file" name="file">
</div>