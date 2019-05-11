
    @if (($errors->has('gallery.*')))
    <div class="alert alert-dismissible alert-warning" style="padding-top: 20px;">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
            <p class="mb-0">{{ $errors->first('gallery.*') }}</p><br>
    </div>
        @endif 

        