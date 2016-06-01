<div class="row">
    <div class="col-lg-8">
        <form class="form-horizontal" role="form" method="POST" action="{{ $updateUrl }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('co2') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">二氧化碳</label>

                <div class="col-md-8">
                    <input type="number" step="0.01" class="form-control" name="co2" value="{{ old('co2') ? old('co2') : $threshold->co2 }}">

                    @if ($errors->has('co2'))
                        <span class="help-block">
                            <strong>{{ $errors->first('co2') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('temp') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">溫度</label>

                <div class="col-md-8">
                    <input type="number" step="0.01" class="form-control" name="temp" value="{{ old('temp') ? old('temp') : $threshold->temp }}">

                    @if ($errors->has('temp'))
                        <span class="help-block">
                            <strong>{{ $errors->first('temp') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('rh') ? ' has-error' : '' }}">
                <label class="col-md-4 control-label">濕度</label>

                <div class="col-md-8">
                    <input type="number" step="0.01" class="form-control" name="rh" value="{{ old('rh') ? old('rh') : $threshold->rh }}">

                    @if ($errors->has('rh'))
                        <span class="help-block">
                            <strong>{{ $errors->first('rh') }}</strong>
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
    </div>
</div>
