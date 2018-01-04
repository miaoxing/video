<?php

namespace Miaoxing\Video\Controller\Admin;

class Video extends \Miaoxing\Plugin\BaseController
{
    protected $controllerName = '视频管理';

    protected $actionPermissions = [
        'index' => '列表',
        'new,create' => '添加',
        'edit,update' => '编辑',
        'destroy' => '删除',
        'audit' => '审核',
    ];

    public function newAction($req)
    {
        return $this->editAction($req);
    }

    public function editAction($req)
    {
        $video = wei()->video()->findOrInitById($req['id']);
        $categories = wei()->category()->notDeleted()->withParent('video')->desc('sort')->fetchAll();

        return get_defined_vars();
    }

    public function indexAction($req)
    {
        switch ($req['_format']) {
            case 'json':
                $videos = wei()->video()->notDeleted();

                // 搜索
                if ($req['search']) {
                    $videos->andWhere('name LIKE ?', '%' . $req['search'] . '%');
                }

                if ($req['categoryId']) {
                    $videos->andWhere('categoryId=?', $req['categoryId']);
                }

                // 分页
                $videos->limit($req['rows'])->page($req['page']);

                // 排序
                $videos->desc('id');

                wei()->event->trigger('beforeVideoFind', [$videos, $req]);

                $data = [];
                foreach ($videos->findAll() as $video) {
                    $category = wei()->category()->notDeleted()->findById($video['categoryId']);
                    $data[] = $video->toArray() + [
                            'categoryName' => $category['name'],
                        ];
                }

                return $this->json('读取列表成功', 1, [
                    'data' => $data,
                    'page' => $req['page'],
                    'rows' => $req['rows'],
                    'records' => $videos->count(),
                ]);

            default:
                return get_defined_vars();
        }
    }

    public function showAction($req)
    {
        $video = wei()->video()->findOneById($req['id']);
        $data = $video->toArray();

        return $this->suc([
            'data' => $data,
        ]);
    }

    public function updateAction($req)
    {
        $validator = wei()->validate([
            'data' => $req,
            'rules' => [
                'name' => [
                ],
            ],
            'names' => [
                'name' => '名称',
            ],
        ]);
        if (!$validator->isValid()) {
            return $this->err($validator->getFirstMessage());
        }

        $urlQuery = parse_url($req['url']);
        parse_str($urlQuery['query']);
        if (!$vid) {
            $pathInfo = pathinfo($req['url']);
            $vid = $pathInfo['filename'];
        }

        $data = [
            'name' => $req['name'],
            'description' => $req['description'],
            'url' => $req['url'],
            'vid' => $vid,
            'pic' => $req['pic'],
            'categoryId' => $req['categoryId'],
            'enable' => 1,
            'type' => $req['type'] ?: 1,
        ];

        wei()->video()->findOrInitById($req['id'])->save($data);

        return $this->suc();
    }

    /**
     * 删除
     */
    public function destroyAction($req)
    {
        wei()->video()->notDeleted()->findOne($req['id'])->softDelete();

        return $this->suc();
    }

    public function auditAction($req)
    {
        $video = wei()->video()->findOneById($req['id']);
        $ret = wei()->audit->audit($video, $req['pass'], $req['description']);

        return $this->ret($ret);
    }
}
