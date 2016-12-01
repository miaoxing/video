<?php $view->layout() ?>
<div class="page-header">
  <div class="pull-right">
    <a class="btn" href="<?= $url('admin/videoCategory/index') ?>">返回列表</a>
  </div>
  <h1>
    微官网
    <small>
      <i class="fa fa-angle-double-right"></i>
      视频栏目管理
    </small>
  </h1>
</div>
<!-- /.page-header -->

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
    <form id="category-form" class="form-horizontal" method="post" role="form">
      <div class="form-group">
        <label class="col-lg-2 control-label" for="parent-id">
          所属栏目
        </label>
        <div class="col-lg-4">
          <select name="parentId" id="parent-id" class="form-control">
            <option value="video">根栏目</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label" for="name">
          <span class="text-warning">*</span>
          名称
        </label>

        <div class="col-sm-4">
          <input type="text" class="form-control" name="name" id="name" data-rule-required="true">
        </div>
      </div>

      <input type="hidden" name="id" id="id"/>

      <input type="hidden" name="type" id="type" value="video"/>

      <div class="clearfix form-actions form-group">
        <div class="col-sm-offset-2">
          <button class="btn btn-info" type="submit">
            <i class="fa fa-check"></i>
            提交
          </button>
          &nbsp; &nbsp; &nbsp;
          <a class="btn" href="<?= $url('admin/videoCategory/index') ?>">
            <i class="fa fa-undo"></i>
            返回列表
          </a>
        </div>
      </div>
    </form>
  </div>
  <!-- PAGE CONTENT ENDS -->
</div><!-- /.col -->
<!-- /.row -->

<?= $block('js') ?>
<script>
  require(['form', 'validator'], function (form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('video')->getTreeToArray()) ?>;
    form.toOptions($('#parent-id'), categoryJson, 'id', 'name');

    var category = <?= $category->toJson() ?>;
    $('#category-form')
      .loadJSON(category)
      .ajaxForm({
        url: '<?= $url('admin/category/update') ?>',
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
          return $form.valid();
        },
        success: function (result) {
          $.msg(result, function () {
            if (result.code > 0) {
              window.location = $.url('admin/videoCategory/index');
            }
          });
        }
      });
  });
</script>
<?= $block->end() ?>
