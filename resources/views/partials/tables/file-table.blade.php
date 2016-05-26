<div class="row">
    <div class="col-md-8 col-md-offset-1">
        <div class="row pt-6">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>帳號</th>
                        <th>檔案名稱</th>
                        <th>上傳日期</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                        <tr>
                            <td>{{$file->user['username']}}</td>
                            <td>{{$file->file_name}}</td>
                            <td>{{ date('Y-m-d', strtotime($file->created_at)) }}</td>
                            <td>
                                <a href="{{ url('/files', $file->id) }}" class="btn btn-link" target="_blank">下載</a>
                            </td>
                            <td>
                                <form id="delete-file" role="form" method="POST" action="{{ url('/files/' . $file->id . '/delete') }}">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-danger">刪除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
