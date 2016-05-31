<div class="row">
    <div class="col-md-8 col-md-offset-1">
        <div class="row pt-6">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>帳號</th>
                        <th>名稱</th>
                        <th>Email</th>
                        <th>帳號上限</th>
                        <th>儀器帳號</th>
                        @role('admin')
                        <th>使用中</th>
                        <th></th>
                        @endrole
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{$client->user['username']}}</td>
                            <td>{{$client->user['name']}}</td>
                            <td>{{$client->user['email']}}</td>
                            <td>{{$client['user_limit']}}</td>
                            <td>{{$client->device_account}}</td>
                            @role('admin')
                            <td>
                                {{$client->user['active'] ? "Yes" : "No"}}
                            </td>
                            <td>
                                <a target="_blank" href="{{ url('?client_id=' . $client->user_id) }}" class="btn btn-link">帳號清單</a>
                            </td>
                            @endrole
                            <td>
                                <a href="{{ url('/accounts/client', $client->user_id) }}" class="btn btn-link">修改</a>
                                @role('admin')
                                <a target="_blank" href="{{ url('/client/' . $client->user_id . '/stats') }}" class="btn btn-link">看紀錄</a>
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
