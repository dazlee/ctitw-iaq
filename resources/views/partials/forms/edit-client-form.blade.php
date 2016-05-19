<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/client/'.$client->user->id) }}">
            {!! csrf_field() !!}

            <div class="col-lg-5">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">客戶Email</label>

                    <div class="col-md-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $client->user['email'] }}" disabled="true">

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
                        <input type="text" class="form-control" name="username" value="{{ $client->user['username'] }}" disabled="true">
                    </div>
                </div>

                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">客戶名稱</label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $client->user['name'] }}">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('user_limit') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">帳號數量</label>

                    <div class="col-md-8">
                        <input type="number" class="form-control" name="user_limit" value="{{ old('user_limit') ? old('user_limit') : $client['user_limit'] }}" min="0" max="16">

                        @if ($errors->has('user_limit'))
                            <span class="help-block">
                                <strong>{{ $errors->first('user_limit') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('device_account') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">儀器帳號</label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="device_account" value="{{ old('device_account') ? old('device_account') : $client['device_account'] }}">

                        @if ($errors->has('device_account'))
                            <span class="help-block">
                                <strong>{{ $errors->first('device_account') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <!-- end of left column -->

            <div class="col-lg-7">
                <div class="form-group">
                    @for($i = 0; $i < 16; $i++)
                        <div class="row pb-6">
                            <label class="col-md-2 control-label">儀器{{$i}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="{{ 'device-name_'.$i }}" value="{{ old('device_name_'.$i) ? old('device_name_'.$i) : $client->devices[$i]['name'] }}" placeholder="儀器位置，填寫此欄位才會顯示資料。">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <!-- end of right column -->

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">送出</button>
                </div>
            </div>
        </form>
    </div>
</div>
