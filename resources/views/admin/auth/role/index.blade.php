@extends('admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <table id="LAY-user-back-role" lay-filter="LAY-user-back-role"></table>
            <script type="text/html" id="table-useradmin-admin">
                <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i
                        class="layui-icon layui-icon-edit"></i>编辑</a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i
                        class="layui-icon layui-icon-delete"></i>删除</a>
            </script>
        </div>
    </div>
@endsection

@push('after-scripts')
    <script type="text/html" id="tableToolbar">
        <div class="layui-btn-container">
            <button class="layui-btn layuiadmin-btn-role" lay-event="add">添加角色</button>
        </div>
    </script>
    <script>
        layui.use(['form', 'table', 'util'], function () {

            var $ = layui.$
                , util = layui.util
                , form = layui.form
                , table = layui.table;

            var events = {
                del: function (data) {
                    layer.confirm('确定删除吗？', function () {
                        var url = '{{ route('admin.api.auth.role.destroy', ['role' => '!role!']) }}'.replace('!role!', data.name);
                        $.ajax({
                            url: url,
                            type: 'delete',
                            success: function() {
                                table.reload('LAY-user-back-role')
                                layer.msg('删除成功', {
                                    offset: '15px',
                                });
                            }
                        });
                    });
                },
                add: function () {
                    events.edit();
                },
                edit: function(data) {
                    var url = data ? '{{ route('admin.auth.role.edit', ['role' => '!role!']) }}'.replace('!role!', data.name) :
                        '{{ route('admin.auth.role.create') }}';
                    layer.open({
                        type: 2
                        , title: '添加新角色'
                        , content: url
                        , area: ['500px', '480px']
                        , btn: ['确定', '取消']
                        , yes: function (index, layero) {
                            var iframeWindow = window['layui-layer-iframe' + index]
                                submit = layero.find('iframe').contents().find('#LAY-auth-role-submit');

                            //监听提交
                            iframeWindow.layui.onevent('submitted', 'form', function (data) {
                                console.log(data);
                                table.reload('LAY-user-back-role')
                                layer.close(index) //关闭弹层
                            })

                            submit.trigger('click')
                        }
                    })
                }
            };

            table.render({
                elem: '#LAY-user-back-role',
                toolbar: '#tableToolbar',
                url: '{{ route('admin.api.auth.roles') }}',
                parseData: function (res) { //res 即为原始返回的数据
                    return {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg':res.message || '加载失败', //解析提示文本
                        'count': res.total || 0, //解析数据长度
                        'data': res.data || [] //解析数据列表
                    };
                },
                cols: [[{
                    field: 'id',
                    width: 80,
                    title: 'ID',
                }, {
                    field: 'name',
                    title: '关键字'
                }, {
                    field: 'title',
                    title: '角色名'
                }, {
                    title: '操作',
                    width: 150,
                    align: 'center',
                    fixed: 'right',
                    toolbar: '#table-useradmin-admin'
                }]],
                text: {
                    none: '还没有创建角色'
                },
                page: true
            });
            table.on("tool(LAY-user-back-role)", function(e) {
                if (events[e.event]) {
                    events[e.event].call(this, e.data);
                }
            });
            util.event('lay-event', events);
        })
    </script>
@endpush

