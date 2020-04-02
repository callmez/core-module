@extends('admin.layouts.app')

@section('content')
    <media-manager></media-manager>
    <div class="layui-card">
        <div class="layui-card-header">
            <button type="button" class="layui-btn" id="upload">
                <i class="layui-icon">&#xe67c;</i>上传
            </button>
            <button type="button" class="layui-btn" id="test1">
                <i class="layui-icon">&#xe67c;</i>添加目录
            </button>
            <button type="button" class="layui-btn" id="test1">
                <i class="layui-icon">&#xe67c;</i>刷新
            </button>
        </div>
        <div class="layui-card-body">
            <div class="layui-row">
                <div id="list"></div>
            </div>
        </div>
    </div>
@endsection

