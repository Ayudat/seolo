<span id="seolo-festives" class="glyphicon glyphicon-calendar"></span>
<div id="seolo-festives-edit" data-saveurl="{{ route('seolo-save-festives') }}" data-geturl="{{ route('seolo-get-festives') }}">
    <div class="panel panel-default margin-bottom-none">
        <div class="panel-heading">
            <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
            {!! t('seolo::app.festives.title') !!}
        </div>
        <div class="panel-body">
            <p class="font-size-14px font-weight-light">{!! t('seolo::app.festives.help') !!}</p>
            <div class="form-group">
                <textarea rows="15" class="form-control" id="seolo-txtfestives" name="seolo-txtfestives"></textarea>
                <span class="help-block margin-bottom-none font-size-13px"></span>
            </div>
        </div>
        <div class="modal-footer">
            <span class="alert alert-success margin-bottom-none">{{ t('seolo::app.festives.success') }}</span>
            <button type="button" class="btn btn-primary btn-sm" id="save">{{ t('seolo::app.caption.save') }}</button>
        </div>
    </div>
</div>
<script src="{{ asset_('js/seolo/festives.js') }}"></script>
