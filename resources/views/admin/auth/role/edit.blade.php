@extends('admin.layouts.app')

@section('title', __('labels.admin.access.roles.management') . ' | ' . __('labels.admin.access.roles.create'))

@section('content')
    <form class="layui-form">
        @csrf
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input class="layui-input" lay-verify="required" type="text" name="title" value="{{ isset($role) ? $role->title : '' }}" placeholder="请输入角色名称" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色关键字</label>
            <div class="layui-input-block">
                <input class="layui-input" lay-verify="required" type="text" name="name" value="{{ isset($role) ? $role->name : '' }}" placeholder="请输入角色关键字" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-block">
                <input class="layui-input" type="text" name="sort" value="{{ isset($role) ? $role->sort : 0 }}"  />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择权限</label>
            <div class="layui-input-block">
                <div id="toolbarDiv" style="max-height:200px; overflow: auto; padding: 15px 5px; border: 1px  solid #e6e6e6; background-color: #fff; border-radius: 2px;">
                    <ul id="LAY-auth-permission-tree" data-id="0"></ul>
                </div>

            </div>
        </div>
        <div class="layui-form-item" lay-iframe-hide>
            <div class="layui-input-block">
                <button class="layui-btn" type="submit" lay-submit lay-filter="LAY-auth-role-submit" id="LAY-auth-role-submit">提交</button>
            </div>
        </div>
    </form>
@endsection


@push('after-scripts')
    <script>
        layui.use(['jquery', 'dtree', 'tree', 'form', 'layer', 'treeTable'], function() {
            var $ = layui.jquery;
            var form = layui.form;
            var layer = layui.layer;
            var tree = layui.tree;
            $(document).on('submitted', function() {
                console.log(123);
            })
            $('.layui-form').click(function() {
                $(this).trigger('submitted');
            });

            function normalizePermissions(permissions, idStart = 0, rolePermissions) {
                var result = [];
                for (let i = 0; i < permissions.length; i++) {
                    var permission = permissions[i];
                    if (permission.parent_id == idStart) {
                        permission.title = permission.title + ' <small title="权限标识">(' + permission.name + ')<small>';
                        permission.children = normalizePermissions(permissions, permission.id, rolePermissions);
                        permission.spread = true;
                        permission.field = 'permissions[]';
                        permission.checked = false;


                        $.each(rolePermissions, function(rolePermission) {
                            if (rolePermission.id == permissions.id) {
                                permission.checked = true;
                            }
                        });

                        result.push(permission);
                    }
                }
                return result;
            }

            $.get("{{ route('admin.api.auth.permissions') }}", function(permissions) {
                var options = {
                    elem: '#LAY-auth-permission-tree',
                    showCheckbox: true,
                    id: 'permissions'
                }
                @if (isset($role))
                $.get("{{ route('admin.api.auth.role', ['role' => $role->name]) }}", function(role) {
                    options.data = normalizePermissions(permissions, 0, role.permissions)
                    tree.render(options);
                });
                @else
                options.data = normalizePermissions(permissions, 0, [])
                tree.render(options);
                @endif
            });

            form.on('submit(LAY-auth-role-submit)', function(data) {
                var $form = $(data.form);
                @if (isset($role))
                    var url = "{{ route('admin.api.auth.role.update', ['role' => $role->name]) }}";
                    var method = "put";
                @else
                    var url = "{{ route('admin.api.auth.role.store') }}";
                    var method = "post";
                @endif
                $.ajax({
                    url: url,
                    type: method,
                    data: $form.serialize(),
                    success: function(result) {
                        layer.msg('操作成功', {
                            offset: '15px',
                            time: 1000,
                            end: function() {
                                layui.event('submitted', 'form', result);
                            }
                        });

                    }
                })
                return false;
            });
        });
    </script>
@endpush
