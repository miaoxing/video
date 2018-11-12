<?php $view->layout('@plugin/layouts/jqm.php') ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('assets/buttonsRow.css') ?>"/>
<link rel="stylesheet" href="<?= $asset('assets/apps/video.css') ?>"/>
<?= $block->end() ?>

<div data-role="content">
  <?php if ($categories) : ?>
    <div class="buttons-row album-buttons-row">
      <a href="<?= wei()->url('video/index') ?>" class="button tab-link">全部</a>
      <?php foreach ($categories as $category) : ?>
        <a href="<?= $url('video', ['categoryId' => $category['id']]) ?>"
          class="button tab-link <?= $req['categoryId'] == $category['id'] ? 'active' : '' ?>">
          <?= $category['name'] ?>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <ul class="video-list">
    <?php foreach ($videos as $video) : ?>
      <li data-icon="false">
        <a href="<?= wei()->url('video/%s', $video['id']) ?>" data-role="none">
          <div class="item-content">
            <img class="item-thumb" src="<?= wei()->video()->getPic($video['vid']) ?>">

            <div class="item-text">
              <span><?= $video['name'] ?></span>
            </div>
          </div>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
