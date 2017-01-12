<?php $view->layout() ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('plugins/admin/css/filter.css') ?>"/>
<?= $block->end() ?>

<div class="page-header">
  <div class="pull-right">
    <a class="btn btn-success" href="<?= $url('admin/video/new') ?>">添加视频</a>
    <a class="btn btn-success" href="<?= $url('admin/video-category') ?>">栏目管理</a>
  </div>
  <h1>
    微官网
    <small>
      <i class="fa fa-angle-double-right"></i>
      视频列表
    </small>
  </h1>
</div>
<!-- /.page-header -->
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <div class="table-responsive">
      <form class="form-horizontal filter-form" id="search-form" role="form">

        <div class="well form-well m-b">
          <div class="form-group form-group-sm">

            <label class="col-md-1 control-label" for="category-id">栏目：</label>

            <div class="col-md-3">
              <select class="form-control" name="categoryId" id="category-id">
                <option value="">全部栏目</option>
              </select>
            </div>

            <?php wei()->event->trigger('searchForm', ['video']); ?>

            <label class="col-md-1 control-label" for="search">标题：</label>

            <div class="col-md-3">
              <input type="text" class="form-control" id="search" name="search">
            </div>

          </div>
        </div>
      </form>

      <table class="table table-bordered table-hover js-record-table record-table">
        <thead>
        <tr>
          <th class="t-6">栏目名称</th>
          <th class="t-4">类型</th>
          <th class="t-10">标题</th>
          <th class="t-10">描述</th>
          <th>视频链接</th>
          <?php wei()->event->trigger('tableCol', ['video']); ?>
          <th class="t-10">操作</th>
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

<script id="video-show-tpl" type="text/html">
  <div class="modal fade show-modal" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="showModalLabel">视频查看</h4>
        </div>
        <div class="modal-body">
          <video src="<%= url %>" controls="controls" class="js-video-play" width="100%" height="100%">
            你的浏览器不支持播放，请换Chrome浏览器查看
          </video>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">关闭</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script id="table-actions" type="text/html">
  <div class="action-buttons">
    <?php wei()->event->trigger('mediaAction', ['video']); ?>

    <% if(type == 1) { %>
    <a href="<%= $.url('video/%s', id) %>" title="查看">
      <i class="fa fa-search-plus bigger-130"></i>
    </a>

    <a href="<%= $.url('admin/video/edit', {id: id}) %>" title="编辑">
      <i class="fa fa-edit bigger-130"></i>
    </a>

    <% } else if(type == 2) { %>
    <a href="javascript:;" class="js-show" title="查看" data-id="<%= id %>">
      <i class="fa fa-search-plus bigger-130"></i>
    </a>
    <% } %>

    <a class="text-danger delete-record"
      data-href="<%= $.url('admin/video/destroy', {id: id}) %>"
      href="javascript:;" title="删除">
      <i class="fa fa-trash-o bigger-130"></i>
    </a>
  </div>
</script>

<?= $block('js') ?>
<script>
  require(['form', 'dataTable', 'template', 'jquery-deparam'], function (form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('video')->getTreeToArray()); ?>;
    form.toOptions($('#category-id'), categoryJson, 'id', 'name');

    $('#search-form').loadParams().update(function () {
      recordTable.reload($(this).serialize(), false);
    });

    var recordTable = $('.js-record-table').dataTable({
      ajax: {
        url: $.queryUrl('admin/video.json')
      },
      columns: [
        {
          data: 'categoryName'
        },
        {
          data: 'type',
          render: function (data, type, full) {
            if (data == 1) {
              return '外部链接';
            }
            return '上传链接';
          }
        },
        {
          data: 'name'
        },
        {
          data: 'description',
          sClass: 'text-left'
        },
        {
          data: 'url',
          sClass: 'text-left',
          render: function (data, type, full) {
            return '(<a class="text-danger" href="' + data + '" target="_blank">点击</a>) ' + data
          }
        },
        <?php wei()->event->trigger('tableData', ['video']); ?>
        {
          data: 'id',
          render: function (data, type, full) {
            return template.render('table-actions', full)
          }
        }
      ]
    });

    recordTable.deletable();

    recordTable.on('click', '.js-show', function () {
      $.getJSON($.url('admin/video/%s', $(this).data('id')), function (ret) {
        if (ret.code > 0) {
          var modal = template.render('video-show-tpl', ret.data);
          $(modal).modal('show');
        } else {
          $.msg(ret);
        }

        // 关闭modal的时候需要停止播放
        $('body').on('hidden.bs.modal', function () {
          $('body').find('.js-video-play').get(0).pause();
        });
      });
    });

  });
</script>
<?= $block->end() ?>
