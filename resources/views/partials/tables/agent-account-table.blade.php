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
                        <th></th>
                        <th></th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @foreach ($agents as $agent)
                        <tr>
                            <td>{{$agent->user['username']}}</td>
                            <td>{{$agent->user['name']}}</td>
                            <td>{{$agent->user['email']}}</td>
                            @role('admin')
                            <td>
                                <a target="_blank" href="{{ url('?agent_id=' . $agent->user_id) }}" class="btn btn-link">客戶清單</a>
                            </td>
                            @endrole
                            <td>
                                <a href="{{ url('/accounts/agent', $agent->user_id) }}" class="btn btn-link">修改</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
