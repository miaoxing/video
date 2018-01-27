<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/video/css/video.css') ?>">
<?= $block->end() ?>

<?php if ($video['pic']) : ?>
  <!-- 分享图 -->
  <div class="hide">
    <img src="<?= $e($video['pic']) ?>">
  </div>
<?php endif ?>

<div class="video">
  <div class="text-center">
    <div id="player"></div>

    <div class="video-title">
      <?= $video['name'] ?>
    </div>

    <div class="video-description">
      <?= $video['description'] ?>
    </div>
  </div>
</div>

<?= $block->js() ?>
<script>
  require(['//imgcache.gtimg.cn/tencentvideo_v1/tvp/js/tvp.player_v2_mobile.js'], function () {
    var video = new tvp.VideoInfo();
    video.setVid("<?=$video['vid']?>");
    var player = new tvp.Player();
    player.create({
      width: $(window).width(),
      height: $(window).width()*0.75,
      video: video,
      modId: "player",
      autoplay: true
    });
  });
</script>
<?= $block->end() ?>
