<div class="row">
    <div class="col-md-8 col-md-offset-1">
        <div class="row pt-6">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>帳號</th>
                        <th>名稱</th>
                        <th>Email</th>
                        @role('admin')
                        <th>部門清單</th>
                        @endrole
                        <th>帳號上限</th>
                        <th>儀器帳號</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        @if(isset($filter_id) && $filter_id == $client->agent_id)
                        <tr>
                            <td>{{$client->user['username']}}</td>
                            <td>{{$client->user['name']}}</td>
                            <td>{{$client->user['email']}}</td>
                            @role('admin')
                            <td>
                                <a target="_blank" href="{{ url('?client_id=' . $client->user_id) }}" class="btn btn-link">部門清單</a>
                            </td>
                            @endrole
                            <td>{{$client['user_limit']}}</td>
                            <th>{{$client['device_account']}}</th>
                            <td>
                                <a href="{{ url('/accounts/client', $client->user_id) }}" class="btn btn-link">修改</a>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
