<div class="row">
    <div class="col-md-8 col-md-offset-1">
        <div class="row pt-6">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>帳號</th>
                        <th>名稱</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{$department['username']}}</td>
                            <td>{{$department['name']}}</td>
                            <td>{{$department['email']}}</td>
                            <td>
                                <a href="{{ url('/accounts/department', $department->id) }}" class="btn btn-link">修改</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
