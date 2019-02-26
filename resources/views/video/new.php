<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/video/css/videos.css') ?>">
<?= $block->end() ?>

<form class="form">
  <div class="form-group">
    <label for="name" class="control-label">
      视频标题
      <span class="text-warning">*</span>
    </label>

    <div class="col-control">
      <input type="text" class="form-control" id="name" name="name">
    </div>
  </div>

  <div class="form-group">
    <label for="category-id" class="control-label">
      视频类型
      <span class="text-warning">*</span>
    </label>

    <div class="col-control">
      <select id="category-id" name="categoryId" class="form-control">
        <option value="">请选择</option>
        <?= wei()->video->getCategoryToOptions(); ?>
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="description" class="control-label">视频描述</label>

    <div class="col-control">
      <textarea class="form-control" id="description" name="description"></textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="file-name-input" class="control-label">
      视频上传
      <span class="text-warning">*</span>
    </label>

    <div class="col-control">
      <div class="input-group">
        <input type="text" class="form-control" id="file-name-input" name="fileNameInput" placeholder="选择文件" readonly>
        <span class="input-group-btn border-left">
          <button type="button" class="text-primary btn btn-secondary form-link btn-file js-btn-file">
            选择文件
          </button>
        </span>
      </div>
    </div>
  </div>

  <div id="file-name" class="tip"></div>
  <div id="file-size" class="tip"></div>
  <div id="file-type" class="tip"></div>

  <div class="form-footer">
    <div class="btn btn-primary btn-block js-submit">上 传</div>
  </div>
</form>

<?= $block->js() ?>
<script>
  require(['plugins/app/libs/jquery-form/jquery.form', 'plugins/video/comps/AjaxFileUpload/ajaxfileupload'], function () {
    $('.js-btn-file').append('<input type="file" accept="video/*" class="js-file" id="file" name="file" />');

    $('.js-file').change(function () {
      var file = $('#file').get(0).files[0];
      if (file) {
        var fileSize = 0;
        if (file.size > 1024 * 1024) {
          fileSize = (Math.round(file.size * 100 / (1024 * 1024)) / 100).toString() + 'MB';
        } else {
          fileSize = (Math.round(file.size * 100 / 1024) / 100).toString() + 'KB';
        }

        $('#file-name').html('文件名: ' + file.name);
        $('#file-size').html('文件大小: ' + fileSize);
        $('#file-type').html('文件类型: ' + file.type);
        $('#file-name-input').val(file.name);
      }
    });

    $('.js-submit').click(function () {
      $.ajaxFileUpload({
        url: $.url('video/upload'), //用于文件上传的服务器端请求地址
        secureuri: false, //一般设置为false
        fileElementId: 'file', //文件上传空间的id
        loading: true,
        data: {
          name: $('#name').val(),
          categoryId: $('#category-id').val(),
          description: $('#description').val()
        },
        dataType: 'json', //返回值类型 一般设置为json
        success: function (ret, status) { //服务器成功响应处理函数
          $.msg(ret, function() {
            if (ret.code == 1) {
              window.location.href = $.url('video/new');
            }
          });
        },
        error: function (ret, status, e) { //服务器响应失败处理函数
          alert(e);
        }
      });
      return false;
    });
  });
</script>
<?= $block->end() ?>
