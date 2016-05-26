<div class="row">
    <div class="col-lg-8">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/department/'.$department->id) }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">客戶名稱</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $department['name'] }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">客戶Email</label>

                <div class="col-md-8">
                    <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $department['email'] }}" disabled="true">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">客戶帳號</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="username" value="{{ $department['username'] }}" disabled="true">
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">送出</button>
                </div>
            </div>
        </form>
    </div>
</div>