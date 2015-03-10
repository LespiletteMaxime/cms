@extends('devise::layouts.public')

@section('content')
    <div class="container pt sp45">
        <div class="dvs-tac mb sp75">
            <img src="<?= URL::asset('/packages/devisephp/cms/img/admin-logo.png') ?>">
        </div>

        @if(Session::has('message-success') || Session::has('message-errors'))
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4 col-sm-offset-4 col-md-offset-4">
                @include('devise::admin.elements.validation')
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-md-4 col-md-offset-4 dvs-tac">
                <form method="POST" action="<?= URL::route('dvs-user-attempt-recover') ?>">
                    <input type="hidden" name="_token" value="<?= csrf_token() ?>">

                    <div class="form-group">
                        <input name="email" type="text" class="form-control" placeholder="Recover by Email" value="<?= old('email') ?>" />
                    </div>

                    <div class="form-group">
                        <button class="dvs-button dvs-button-primary">Send Recovery Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop