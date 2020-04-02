@extends('admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="LAY-module" lay-filter="LAY-module"></table>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/html" id="table-useradmin-admin">
        @{{# if(d.enabled) { }}
            @{{# if(d.can_disable) { }}
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="disable"><i class="layui-icon layui-icon-delete" ></i>禁用</a>
            @{{# } else { }}
                <a class="layui-btn layui-btn-disabled layui-btn-xs"><i class="layui-icon layui-icon-delete" ></i>禁用</a>
            @{{# } }}
        @{{# } else { }}
            <a class="layui-btn layui-btn-success layui-btn-xs" lay-event="enable"><i class="layui-icon layui-icon-delete" ></i>启用</a>
        @{{# } }}
    </script>
    <script>
        layui.use(['form', 'table', 'util'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table;

            var events = {
                disable: function (data) {
                    layer.confirm('确定禁用该模块吗？', function () {
                        var url = '{{ route('admin.api.module.disable', ['module' => '!module!']) }}'.replace('!module!', data.name);
                        $.ajax({
                            url: url,
                            type: 'post',
                            success: function() {
                                table.reload('LAY-module')
                                layer.msg('模块已禁用', {
                                    offset: '15px',
                                });
                            }
                        });
                    });
                },
                enable: function(data) {
                    var url = '{{ route('admin.api.module.enable', ['module' => '!module!']) }}'.replace('!module!', data.name);
                    $.ajax({
                        url: url,
                        type: 'post',
                        success: function() {
                            table.reload('LAY-module')
                            layer.msg('模块启用成功', {
                                offset: '15px',
                            });
                        }
                    });
                }
            };

            table.render({
                elem: '#LAY-module',
                url: '{{ route('admin.api.module.modules') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.length, //解析数据长度
                        'data': res || [] //解析数据列表
                    };
                },
                cols: [[{
                    field: 'name',
                    title: '模块名',
                }, {
                    field: 'alias',
                    title: '关键字'
                }, {
                    field: 'description',
                    title: '描述'
                }, {
                    title: '操作',
                    width: 150,
                    align: 'center',
                    fixed: 'right',
                    toolbar: '#table-useradmin-admin'
                }]],
                text: {
                    none: '没有可用模块'
                },
            });
            table.on("tool(LAY-module)", function(e) {
                if (events[e.event]) {
                    events[e.event].call(this, e.data);
                }
            });
        })
    </script>
@endpush

