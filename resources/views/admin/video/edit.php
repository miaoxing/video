<?php $view->layout() ?>
<div class="page-header">
  <div class="pull-right">
    <a class="btn btn-default" href="<?= $url('admin/video/index') ?>">返回列表</a>
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
    <form class="form-horizontal js-video-form" method="post" role="form">

      <div class="form-group">
        <label class="col-sm-2 control-label" for="name">
          <span class="text-warning">*</span>
          标题
        </label>

        <div class="col-sm-4">
          <input type="text" class="form-control" name="name" id="name" data-rule-required="true">
        </div>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label" for="category-id">
          <span class="text-warning">*</span>
          栏目
        </label>

        <div class="col-sm-4">
          <select class="form-control" name="categoryId" id="category-id" data-rule-required="true">
            <option value="" selected>无</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="col-lg-2 control-label" for="thumb">
          封面
        </label>

        <div class="col-lg-4">
          <div class="input-group">
            <input type="text" class="form-control js-thumb" id="pic" name="pic">
            <span class="input-group-btn">
                <button id="select-thumb" class="btn btn-white" type="button">
                  <i class="fa fa-picture-o"></i>
                  选择图片
                </button>
            </span>
          </div>
        </div>
        <label class="col-lg-6 help-text" for="no">
          支持JPG、PNG格式，建议大图900像素 * 500像素，小图200像素 * 200像素，小于1M
        </label>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label" for="url">
          <span class="text-warning">*</span>
          视频链接
        </label>

        <div class="col-sm-4">
          <input type="text" class="form-control" name="url" id="url" data-rule-required="true">
        </div>

        <label class="col-lg-6 help-text" for="url">
          请输入腾讯视频播放页url，例如：http://v.qq.com/cover/i/i7ij0fuhsa4gq6k.html?vid=n0012xcaimy
        </label>
      </div>

      <div class="form-group">
        <label class="col-sm-2 control-label" for="description">
          <span class="text-warning">*</span>
          描述
        </label>

        <div class="col-sm-4">
          <textarea class="form-control" name="description" id="description" data-rule-required="true"></textarea>
        </div>
      </div>

      <input type="hidden" name="id" id="id"/>

      <div class="clearfix form-actions form-group">
        <div class="col-sm-offset-2">
          <button class="btn btn-primary" type="submit">
            <i class="fa fa-check"></i>
            提交
          </button>
          <?php $event->trigger('renderAdminFormActions', ['video']) ?>
          &nbsp; &nbsp; &nbsp;
          <a class="btn btn-default" href="<?= $url('admin/video/index') ?>">
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
  require(['form', 'ueditor', 'validator'], function (form) {
    var categoryJson = <?= json_encode(wei()->category()->notDeleted()->withParent('video')->getTreeToArray()) ?>;
    form.toOptions($('#category-id'), categoryJson, 'id', 'name');

    var video = <?= $video->toJson() ?>;
    $('.js-video-form')
      .loadJSON(video)
      .ajaxForm({
        url: '<?= $url('admin/video/update') ?>',
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
          return $form.valid();
        },
        success: function (result) {
          $.msg(result, function () {
            if (result.code > 0) {
              window.location = $.url('admin/video/index');
            }
          });
        }
      });

    // 点击选择图片
    $('.js-thumb').imageInput();
  });
</script>
<?= $block->end() ?>
