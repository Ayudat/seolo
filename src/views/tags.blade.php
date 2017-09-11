<?php $routeName = Illuminate\Support\Facades\Route::current()->getName(); ?>
<span id="seolo-tags" class="glyphicon glyphicon-tags"></span>
<div id="seolo-tags-edit" data-saveurl="{{ route('seolo-save-tags') }}" data-route="{{ $routeName }}">
    <div class="panel panel-default margin-bottom-none">
        <div class="panel-heading">
            <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
            {!! t('seolo::app.tags.title', 1, ['route' => route($routeName)]) !!}
        </div>
        <div class="panel-body">
            @foreach (['tab', 'title', 'description'] as $foo)
            <div class="form-group">
                <label for="seolo-tag_{{ $foo }}">{{ t('seolo::app.tags.label_' . $foo) }} <span class="badge" id="seolo-tag_{{ $foo }}_chars">0</span></label>
                <input type="text" class="form-control" id="seolo-tag_{{ $foo }}" name="seolo-tag_{{ $foo }}">
            </div>
            @endforeach
        </div>
        <div class="modal-footer">
            <span class="alert alert-success margin-bottom-none">{{ t('seolo::app.tags.success') }}</span>
            <button type="button" class="btn btn-primary btn-sm" id="save">{{ t('seolo::app.caption.save') }}</button>
        </div>
    </div>
</div>
<script src="{{ asset_('js/seolo/tags.js') }}"></script>
