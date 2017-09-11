<div id="seolo-text" data-saveurl="<?=route('seolo-save-text')?>">
    <div class="panel panel-default margin-bottom-none">
        <div class="panel-heading">
            <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
            {{ t('seolo::app.texts.title') }} <span id="text-id"></span>
        </div>
        <div class="panel-body">
            <textarea rows="6" class="form-control"></textarea>
            <div id="saved" class="hidden"></div>
        </div>
        <div class="modal-footer">
            <small class="font-size-12px padding-right-md">{!! t('seolo::app.texts.help') !!}</small>
            <button type="button" class="btn btn-default btn-sm bold" id="pos">&gt;</button>
            <button type="button" class="btn btn-default btn-sm" id="test">{{ t('seolo::app.caption.test') }}</button>
            <button type="button" class="btn btn-primary btn-sm" id="save">{{ t('seolo::app.caption.save') }}</button>
        </div>
    </div>
</div>
<script src="{{ asset_('js/seolo/texts.js') }}"></script>
