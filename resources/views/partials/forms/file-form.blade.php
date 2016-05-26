<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/client/' . $user_id . '/file') }}"  enctype="multipart/form-data">
            {!! csrf_field() !!}

            <div class="col-lg-5">
                <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                    <label>上傳檔案</label>
                    <input type="file" name="file">

                    @if ($errors->has('file'))
                        <span class="help-block">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary">上傳</button>
                </div>
            </div>
        </form>
    </div>
</div>
