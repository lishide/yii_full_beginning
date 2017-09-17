<div class="nav">
    <ul class="cc">
        <li class="current">
            <?php echo CHtml::link('欢迎信息', array('/admin/default/welcome'));?>
        </li>
    </ul>
</div>

<div class="h_a">用户信息</div>
<div class="table_full">
    <table width="100%">
        <colgroup>
            <col class="th" width="150"/>
            <col/>
        </colgroup>
        <tbody>
            <tr>
                <th><label>您的账号：</label></th>
                <td><?php echo App()->user->name;?></td>

            </tr>
            <tr>
                <th><label for="">您的角色：</label></th>
                <td>
                    <?php
                    $roles = Rights::getAssignedRoles(App()->user->id);
                    $index=0;
                    foreach($roles as $k => $role) {
                        echo ($index>0 ? '  ' : '') . $role->description;
                        $index++;
                    }
                    ?>
                </td>
                <th><label for="">您的IP：</label></th>
                <td><?php echo $_SERVER['REMOTE_ADDR'];?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="h_a">系统信息</div>
<div class="table_full">
    <table width="100%">
        <colgroup>
            <col class="th" width="150"/>
            <col/>
        </colgroup>
        <tbody>
            <tr>
                <th><label>系统名称：</label></th>
                <td><?php echo App()->name;?> 后台管理系统</td>
                <th><label for="">系统版本：</label></th>
                <td>V 1.0</td>
            </tr>
            <tr>
                <th><label for="">系统时间：</label></th>
                <td><?php echo date('Y-m-d H:i');?></td>
                <th><label for="">服务器系统：</label></th>
                <td><?php echo php_uname('s');?></td>
            </tr>
            <tr>
                <th><label for="">PHP运行方式：</label></th>
                <td><?php echo php_sapi_name();?></td>
                <th><label for="">PHP版本：</label></th>
                <td><?php echo PHP_VERSION;?></td>
            </tr>
            <tr>
                <th><label for="">服务器：</label></th>
                <td><?php echo  $_SERVER["HTTP_HOST"];?></td>
                <th><label for="">WEB端口：</label></th>
                <td><?php echo  $_SERVER['SERVER_PORT'];?></td>
            </tr>
        </tbody>
    </table>
</div>