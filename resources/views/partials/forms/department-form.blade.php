<div class="row">
    <div class="col-lg-8">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/department') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">部門名稱</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">部門Email</label>

                <div class="col-md-8">
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">部門帳號</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="username" value="{{ old('username') }}">

                    @if ($errors->has('username'))
                        <span class="help-block">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">密碼</label>

                <div class="col-md-8">
                    <input type="password" class="form-control" name="password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">再次輸入密碼</label>

                <div class="col-md-8">
                    <input type="password" class="form-control" name="password_confirmation">

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('device_id') ? ' has-error' : '' }} form-inline">
                <label class="col-md-4 control-label">儀器總表</label>
                <div class="col-md-8">
                    <select class="form-control" name="device_id" value="{{ old('device_id') }}">
                        @for ($i = 1; $i <= 16; $i++)
                            @if (old('device_id') !== (string)$i)
                                <option value="{{$i}}">{{$i}}</option>
                            @else
                                <option value="{{$i}}" selected="true">{{$i}}</option>
                            @endif
                        @endfor
                    </select>
                    @if ($errors->has('device_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('device_id') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">送出</button>
                    <button type="reset" class="btn btn-default">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>
