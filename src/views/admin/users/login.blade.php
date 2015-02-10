@extends('devise::layouts.public')

@section('content')
    <div class="container pt sp45">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-sm-offset-3 col-md-offset-3 col-lg-offset-3">
                @include('devise::admin.elements.validation')
            </div>
        </div>

        <div class="tac mb sp75">
            <img src="<?= URL::asset('/packages/devisephp/cms/img/admin-logo.png') ?>">
        </div>

        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <?= Form::open(array('method' => 'POST', 'route' => 'user-attempt-login')) ?>

                    @if(URL::previous() && Request::url() != URL::previous())
                        <input type="hidden" name="intended" value="<?= URL::previous() ?>">
                    @endif

                    <div class="form-group">
                        <input name="email" type="text" class="form-control" placeholder="Email" />
                    </div>

                    <div class="form-group">
                        <input name="password" type="password" class="form-control" placeholder="Password" />
                    </div>

                    <div class="form-group">
                        <button class="dvs-button dvs-button-primary">Log In to Administration</button>
                    </div>
                <?= Form::close() ?>
            </div>
        </div>
    </div>
@stop