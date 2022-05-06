<div id="changePasswordModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Поменять пароль</h5>
                <button type="button" aria-label="Close" class="close outline-none" data-dismiss="modal">×</button>
            </div>
            <form method="POST" id='changePasswordForm' action="/password/reset">
                @csrf

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="alert alert-danger d-none" id=""></div>
                        <input type="hidden" name="is_active" value="1">
                        <input type="hidden" name="email" id="editPasswordValidationErrorsBox" value="{{ Auth::user()->email }}">
                        {{csrf_field()}}
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label>Текущий пароль:</label><span
                                    class="required confirm-pwd"></span><span class="required">*</span>
                            <div class="input-group">
                                <input class="form-control input-group__addon" id="pfCurrentPassword" type="password"
                                       name="password_current" required>
                                <div class="input-group-append input-group__icon">
                                    <span class="input-group-text changeType">
                                        <i class="icon-ban icons"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Новый пароль:</label><span
                                    class="required confirm-pwd"></span><span class="required">*</span>
                            <div class="input-group">
                                <input class="form-control input-group__addon" id="pfNewPassword" type="password"
                                       name="password" required>
                                <div class="input-group-append input-group__icon">
                                    <span class="input-group-text changeType">
                                        <i class="icon-ban icons"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Подтвердите пароль:</label><span
                                    class="required confirm-pwd"></span><span class="required">*</span>
                            <div class="input-group">
                                <input class="form-control input-group__addon" id="pfNewConfirmPassword" type="password"
                                       name="password_confirmation" required>
                                <div class="input-group-append input-group__icon">
                                    <span class="input-group-text changeType">
                                        <i class="icon-ban icons"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary" id="btnPrPasswordEditSave" data-loading-text="<span class='spinner-border spinner-border-sm'></span> Processing...">Сохранить</button>
                        <button type="button" class="btn btn-light ml-1" data-dismiss="modal">Отмена
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
