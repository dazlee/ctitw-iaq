<div class="row">
    <div class="col-lg-8">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/department/'.$department->id) }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">帳號</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="username" value="{{ $department['username'] }}" disabled="true">
                </div>
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">Email</label>

                <div class="col-md-8">
                    <input type="email" class="form-control" name="email" value="{{ old('email') ? old('email') : $department['email'] }}">

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">名稱</label>

                <div class="col-md-8">
                    <input type="text" class="form-control" name="name" value="{{ old('name') ? old('name') : $department['name'] }}">

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
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

            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">送出</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">刪除客戶帳號</h3>
            </div>
        </div>
        <form id="delete-department" class="form-horizontal" role="form" method="POST" action="{{ url('/accounts/department/'.$department->id).'/delete' }}">
            {!! csrf_field() !!}
            <div class="form-group">
                <div class="col-md-1">
                    <button id="delete-department-button" type="button" class="btn btn-danger">刪除帳號</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var deleteBtn = document.getElementById("delete-department-button");
    deleteBtn.addEventListener("click", function (e) {
        e.preventDefault();
        if (confirm ("確定刪除帳號？\n此動作將無法復原")) {
            document.getElementById("delete-department").submit();
        }
    });
</script>
