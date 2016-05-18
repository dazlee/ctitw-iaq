<div class="row">
    <div class="col-lg-6">
        <form role="form">
            <div class="form-group">
                <label>部門儀器編號</label>
                <select class="form-control">
                    @for ($i = 0; $i < 16; $i++)
                        <option>{{$i}}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="btn btn-default">送出</button>
            <button type="reset" class="btn btn-default">重置</button>
        </form>
    </div>
</div>
