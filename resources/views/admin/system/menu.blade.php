@extends('admin.layouts.app')

@section('content')
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">系统菜单管理</div>
                <div class="layui-card-body" id="menu"></div>
            </div>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/javascript">
        layui.use(['system/menu'], function() {
            const systemMenu = layui['system/menu'];

            systemMenu.render({
                elem: '#menu',
                url: '{{ route('admin.api.system.menu') }}'
            })
        });
    </script>
@endpush
