<?php

namespace Miaoxing\Video;

class Plugin extends \Miaoxing\Plugin\BasePlugin
{
    protected $name = '视频管理';

    protected $description = '';

    public function onAdminNavGetNavs(&$navs, &$categories, &$subCategories)
    {
        $navs[] = [
            'parentId' => 'app-site',
            'url' => 'admin/video/index',
            'name' => '视频管理',
        ];
    }

    public function onLinkToGetLinks(&$links, &$types)
    {
        $types['video'] = [
            'name' => '视频',
            'sort' => 800,
        ];

        $links[] = [
            'typeId' => 'video',
            'name' => '视频列表',
            'url' => 'video',
        ];

        foreach (wei()->category()->notDeleted()->withParent('video')->desc('sort')->getTree() as $category) {
            $links[] = [
                'typeId' => 'video',
                'name' => '视频列表：' . $category['name'],
                'url' => 'video?categoryId=' . $category['id'],
            ];
        }
    }
}
