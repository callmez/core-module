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
    {{ style('static/layuiadmin/layui/css/layui.css') }}
    {{ style('static/layuiadmin/style/admin.css') }}
    {{ style(mix('build/css/admin.css')) }}
    @stack('after-styles')
</head>

<body>

    <div id="LAY_app" class="layui-fluid">
        @yield('content')
    </div><!--layui-fluid-->

    <!-- Scripts -->
    @stack('before-scripts')
    {!! script('static/layuiadmin/layui/layui.js') !!}
    {!! script(mix('build/js/manifest.js')) !!}
    {!! script(mix('build/js/vendor.js')) !!}
    {!! script(mix('build/js/admin.js')) !!}
    @stack('after-scripts')
</body>
</html>
