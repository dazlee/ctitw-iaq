<div class="row">
    <div class="col-lg-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/client/'.$client->user->id) }}">
            {!! csrf_field() !!}

            <div class="col-lg-5">

                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">客戶帳號</label>

                    <div class="col-md-8">
                        <input type="text" class="form-control" name="username" value="{{ $client->user['username'] }}" disabled="true">
                    </div>
                </div>

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">客戶Email</label>

                    <div class="col-md-8">
                        <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $client->user['email'] }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
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

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="col-md-4 control-label">密碼（如不修改，空白即可）</label>

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
                <div class="col-md-8">
                    <button type="submit" class="btn btn-primary">送出</button>
                </div>
            </div>
        </form>

        @role('admin')
        @if ($client->user['active'])
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">停權：使用者將無法登入及使用</h3>
            </div>
        </div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/agent/'.$client->user->id).'/deactive' }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="col-md-1">
                    <button type="submit" class="btn btn-danger">停權</button>
                </div>
            </div>
        </form>
        @endif
        @if (!$client->user['active'])
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">復權：恢復使用者登入及使用權利</h3>
            </div>
        </div>
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/agent/'.$client->user->id).'/active' }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success">復權</button>
                </div>
            </div>
        </form>
        @endif
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">除權：將會刪除使用者所有資料，無法復原</h3>
            </div>
        </div>
        <form id="delete-client" class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/agent/'.$client->user->id).'/delete' }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="col-md-1">
                    <button id="delete-client-button" type="button" class="btn btn-warning">除權</button>
                </div>
            </div>
        </form>
        @endrole
    </div>
</div>
<script>
    var deleteBtn = document.getElementById("delete-client-button");
    deleteBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (confirm ("確定除權？\n此動作將無法復原")) {
            document.getElementById("delete-client").submit();
        }
    });
</script>
