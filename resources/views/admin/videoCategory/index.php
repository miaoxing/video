<?php $view->layout() ?>

<div class="page-header">
  <div class="float-right">
    <form id="video-form" class="form-horizontal" method="post" role="form">
      <a class="btn btn-success" href="<?= $url('admin/video-category/new') ?>">添加栏目</a>
      <a class="btn btn-default" href="<?= $url('admin/video/index') ?>">返回列表</a>
    </form>
  </div>
  <h1>
    视频管理
    <small>
      <i class="fa fa-angle-double-right"></i>
      视频标签列表
    </small>
  </h1>
</div>
<!-- /.page-header -->
<div class="row">
  <div class="col-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="table-responsive">
      <table id="record-table" class="table table-bordered table-hover">
        <thead>
        <tr>
          <th>ID</th>
          <th>名称</th>
          <th>操作</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
    <!-- /.table-responsive -->
    <!-- PAGE CONTENT ENDS -->
  </div>
  <!-- /col -->
</div>
<!-- /row -->

<script id="table-actions" type="text/html">
  <div class="action-buttons">
    <a href="<%= $.url('admin/videoCategory/edit', {id: id}) %>" title="编辑">
      <i class="fa fa-edit bigger-130"></i>
    </a>
    <a class="text-danger delete-record" data-href="<%= $.url('admin/videoCategory/destroy', {id: id}) %>"
      href="javascript:" title="删除">
      <i class="fa fa-trash-o bigger-130"></i>
    </a>
  </div>
</script>

<?= $block->js() ?>
<script>
  require(['plugins/admin/js/data-table', 'plugins/app/libs/artTemplate/template.min', 'form'], function () {
    $('#search-form').loadParams().update(function () {
      recordTable.reload($(this).serialize());
    });

    var recordTable = $('#record-table').dataTable({
      ajax: {
        url: $.queryUrl('admin/video-category.json', {parentId: 'video'})
      },
      columns: [
        {
          data: 'id'
        },
        {
          data: 'name'
        },
        {
          data: 'id',
          sClass: 'text-center',
          render: function (data, type, full) {
            return template.render('table-actions', full)
          }
        }
      ]
    });

    recordTable.deletable();
  });
</script>
<?= $block->end() ?>
