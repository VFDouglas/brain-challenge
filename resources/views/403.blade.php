<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Access Denied</title>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{asset('/css/403.css')}}">
    </head>
    <body>
        <div class="text-wrapper text-center">
            <div class="title lh-sm" data-content="404">
                {{__('403.title')}}
            </div>
            <div class="subtitle">
                {{__('403.subtitle')}}
            </div>
            <div class="isi col-12 col-sm-11 col-md-10 col-lg-9 col-xl-8">
                {{__($message ?? '403.event_access_message')}}
            </div>
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <form action="logout" method="post">
                        @csrf
                        <button type="submit" class="col-12 btn bg-danger px-4 px-sm-5 py-4 rounded-5 fs-5 mx-2">
                            {{strtoupper(__('403.logout'))}}
                        </button>
                    </form>
                </div>
                <div class="col-12 col-sm-6 my-2">
                    <a href="/" class="col-12 btn bg-primary px-4 px-sm-5 py-4 rounded-5 fs-5 mx-2 text-dark">
                        {{strtoupper(__('403.homepage_button_text'))}}
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
