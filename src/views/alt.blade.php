<div id="seolo-alt" class="modal fade" tabindex="-1" role="dialog" data-saveurl="<?=route('seolo-save-alt')?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ t('seolo::app.alts.title') }} <span id="alt-id"></span></h4>
            </div>
            <div class="modal-body">
                <input class="form-control"/>
                <div id="saved" class="hidden"></div>
            </div>
            <div class="modal-footer">
                <span class="alert alert-success margin-bottom-none">{{ t('seolo::app.alts.success') }}</span>
                <button type="button" class="btn btn-primary btn-sm" id="save">{{ t('seolo::app.caption.save') }}</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset_('js/seolo/alts.js') }}"></script>
