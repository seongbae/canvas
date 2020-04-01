 <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputEmail3"  name="name" value="{{ $media != null ? $media->name : null }}">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                      <textarea name="description" class="form-control">{{ $media ? $media->description : null}}</textarea>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                        @if ($media)
                          <img src="{{$media->path}}" style="width:200px;">
                        @endif
                       <input id="profile_image" type="file" class="form-control" name="file_url">
                        
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info">Save</button>
                  <a href="{{URL::previous()}}" class="btn btn-default">Cancel</a>
                </div>