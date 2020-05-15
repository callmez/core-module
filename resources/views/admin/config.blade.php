@extends('core::admin.layouts.app')

@section('content')
    <div class="layui-card">
        <div class="layui-card-body">
            <script type="text/html" id="tool-bar">
            </script>
            <table id="config-table" lay-filter="config-table"></table>
        </div>
    </div>
    <div class="" id="add-config-box" style="display:none;">
        <form class="layui-form" action="">
            <div class="layui-form-item">
                <label class="layui-form-label">模块</label>
                <div class="layui-input-block">
                    <input type="text" name="module" required  lay-verify="required" placeholder="请输入模块名" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">Key</label>
                <div class="layui-input-block">
                    <input type="text" name="key" required  lay-verify="required" placeholder="请输入Key" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">Value</label>
                <div class="layui-input-block">
                    <input type="text" name="value" required  lay-verify="required" placeholder="请输入Value" autocomplete="off" class="layui-input">
                </div>
            </div>
        </form>
    </div>
@endsection

@push('after-scripts')

    <script>
        layui.use('table', function () {
            var table = layui.table;
            var $ = layui.$;
            //第一个实例
            table.render({
                elem: '#config-table'
                , url: '{{ route('admin.api.config.index')}}' //数据接口
                , parseData: function (res) { //res 即为原始返回的数据
                    let result = {
                        'code': res.message ? 400 : 0, //解析接口状态
                        'msg': res.message || '加载失败', //解析提示文本
                        'count': res.length, //解析数据长度
                        'data': [] //解析数据列表
                    }
                    if (result.code === 0) {
                        for (let p in res) {
                            for (let q in res[p].value) {
                                result.data.push({
                                    'module': res[p].module,
                                    'key': q,
                                    'value': res[p].value[q]
                                });

                            }
                        }
                    }
                    console.log(result);
                    return result
                }
                , page: false //开启分页
                , cols: [[ //表头
                    {field: 'module', title: '模块', width: 300, sort: true, fixed: 'left', edit: 'text'},
                    {field: 'key', title: 'Key', width: 300, sort: true, fixed: 'left', edit: 'text'}
                    , {field: 'value', title: 'Value', fixed: 'left', edit: 'text'}

                ]]
                ,toolbar: 'tool-bar' //开启头部工具栏，并为其绑定左侧模板
                ,defaultToolbar: [ {
                    title: '提示' //标题
                    ,layEvent: 'add_config' //事件名，用于 toolbar 事件中使用
                    ,icon: 'layui-icon-addition' //图标类名
                }]
            });

            table.on('edit(config-table)', function (obj) {
                $.ajax({
                    url: '{{route('admin.api.config.update')}}',
                    type: 'patch',
                    data: {key: obj.data.key, value: obj.data.value, module: obj.data.module},
                    success: function (res) {
                        console.log(res)
                    }
                });
            });

            table.on('toolbar(config-table)', function(obj){
                var checkStatus = table.checkStatus(obj.config.id);
                switch(obj.event){
                    //自定义头工具栏右侧图标 - 提示
                    case 'add_config':
                        $("#add-config-box").show();
                        break;
                };
            });

        });
    </script>
@endpush


