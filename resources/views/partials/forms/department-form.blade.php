<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">開新部門</h3>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <form role="form" method="post" action="{{ action('AccountsController@createDepartment') }}">
            @if(count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif

            <input name="_token" type="hidden" value="{{ csrf_token() }}"/>
            <div class="form-group">
                <label>部門名稱</label>
                <input class="form-control" name="name" placeholder="部門" required>
            </div>
            <div class="form-group">
                <label>部門帳號</label>
                <input class="form-control" name="email" required>
            </div>
            <div class="form-group">
                <label>部門密碼</label>
                <input class="form-control" name="password" type="password" required>
            </div>
            <div class="form-group">
                <label>部門電話</label>
                <input class="form-control" name="phone">
            </div>
            <div class="form-group">
                <label>儀器總表</label>
                @foreach($devices as $device)
                <div class="radio">
                    <label>
                        <input type="radio" name="device_id" value={{$device->id}} checked>{{$device->id}}
                    </label>
                </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-default">送出</button>
            <button type="reset" class="btn btn-default">重置</button>
        </form>
    </div>
</div>
