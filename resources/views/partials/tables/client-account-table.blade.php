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
                        <th>儀器數量</th>
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
                            <th>還沒算</th>
                            <td>
                                <a href="{{ url('/accounts/client', $client->user->id) }}" class="btn btn-link">修改</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
