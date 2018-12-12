<?php
/**
 * Created by PhpStorm.
 * User: yyswoole
 * Date: 2018/12/2
 * Time: 20:36
 */

?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css" />
    <!-- <link rel="stylesheet" type="text/css" href="./css/bootstrap-theme.min.css" /> -->

    <script type="text/javascript" src="./js/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>

    <style>

body{
    width: 100%;
    height: auto;
    text-align: center;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .center{
    width: auto;
    height: auto;
    margin: auto;
}
    </style>
</head>
<body>

<div class="center">

    <!-- 模态框（Modal） -->
    <h2>模态框（Modal）插件事件</h2>
    <!-- 按钮触发模态框 -->
    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal">开始演示模态框</button>
    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>
                </div>
                <div class="modal-body"><? echo $test;?>点击关闭按钮检查事件功能。</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary">提交更改</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

<script>
$(function () {
    $('#myModal').modal('hide')
    });
</script>
<script>
$(function () {
    $('#myModal').on('hide.bs.modal',
        function () {
            alert('嘿，我听说您喜欢模态框...');
        })
    });
</script>

</body>
</html>