<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/video/css/videos.css') ?>">
<?= $block->end() ?>

<ul class="js-video-list list">
</ul>

<script type="text/html" class="js-video-item-tpl">
  <li class="video-item">
    <div class="video">
      <div class="video-container text-center">
        <div id="<%= id %>-player" class="js-player" data-id="<%= id %>"></div>

        <div class="video-title">
          <%= name %>
        </div>

        <div class="video-description">
          <%= description %>
        </div>
      </div>
    </div>
  </li>
</script>

<?= $block->js() ?>
<script>
  require([
    'comps/artTemplate/template.min',
    'comps/dropdown-menu/dropdown-menu',
    '//imgcache.gtimg.cn/tencentvideo_v1/tvp/js/tvp.player_v2_mobile.js'
  ], function () {
    template.helper('$', $);

    var list = $('.js-video-list').list({
      url: '<?= $url->query('video/list.json') ?>',
      template: template.compile($('.js-video-item-tpl').html()),
      localData: <?= json_encode($ret); ?>,
      afterRender: function (item) {
        var video = new tvp.VideoInfo();
        var listData = item.data('list-data');
        video.setVid(listData['vid']);
        var player = new tvp.Player();
        player.create({
          width: $(window).width(),
          height: $(window).width() * 0.75,
          video: video,
          modId: item.data('list-data')['id'] + "-player",
          pic: item.data('list-data')['pic'],
          autoplay: false
        });
      }
    });
  });
</script>
<?= $block->end() ?>
