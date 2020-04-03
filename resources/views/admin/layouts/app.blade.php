<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', app_name())</title>
    <meta name="description" content="@yield('meta_description', 'TokenIO')">
    @yield('meta')

    @stack('before-styles')
    {{ style('js/layuiadmin/layui/css/layui.css') }}
    {{ style('js/layuiadmin/style/admin.css') }}
    {{ style(mix('css/admin.css')) }}
    @stack('after-styles')
</head>

<body>

    <div id="LAY_app" class="layui-fluid">
        @yield('content')
    </div><!--layui-fluid-->

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script('js/layuiadmin/layui/layui.js') !!}
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/admin.js')) !!}
    @stack('after-scripts')
</body>
</html>
