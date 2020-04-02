@extends('admin.layouts.app')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md15">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">系统信息</div>
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10">
                                <div class="layui-col-xs6">
                                    <a class="layadmin-backlog-body" href="javascript:">
                                        <h3>服务器操作系统</h3>
                                        <p><cite>{{php_uname('s') . php_uname('r')}}</cite></p>
                                    </a>
                                </div>
                                <div class="layui-col-xs6">
                                    <a class="layadmin-backlog-body" href="javascript:">
                                        <h3>Web 服务器:</h3>
                                        <p><cite>{{ $_SERVER['SERVER_SOFTWARE'] }}</cite></p>
                                    </a>
                                </div>
                                <div class="layui-col-xs6">
                                    <a class="layadmin-backlog-body" href="javascript:">
                                        <h3>PHP 版本</h3>
                                        <p><cite>{{ PHP_VERSION }}</cite></p>
                                    </a>
                                </div>
                                <div class="layui-col-xs6">
                                    <a class="layadmin-backlog-body" href="javascript:">
                                        <h3>Laravel 版本</h3>
                                        <p><cite>{{ app()->version() }}</cite></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
