<?php

namespace Miaoxing\Video\Controller;

use Miaoxing\File\Service\File;

class Video extends \Miaoxing\Plugin\BaseController
{
    protected $guestPages = ['video'];

    public function indexAction($req)
    {
        var_dump($_SERVER);die;


        $this->page->setTitle('视频列表');
        $categories = wei()->category()->notDeleted()->withParent('video')->desc('sort')->fetchAll();
        $videos = wei()->video()->orderBy('id', 'desc');
        if ($req['categoryId']) {
            $videos->where('categoryId=?', $req['categoryId']);
        }
        $videos = $videos->fetchAll();

        return get_defined_vars();
    }

    public function showAction($req)
    {
        $this->page->setTitle('视频');
        $video = wei()->video()->findById($req['id']);

        return get_defined_vars();
    }

    public function listAction($req)
    {
        $videos = wei()->video();
        $videos->limit(5)->page($req['page'])->desc('id');
        $data = [];
        foreach ($videos->findAll() as $video) {
            $data[] = $video->toArray();
        }

        $ret = [
            'data' => $data,
            'records' => $videos->count(),
        ];

        switch ($req['_format']) {
            case 'json':
                return $this->ret($ret);
            default:
                return get_defined_vars();
        }
    }

    public function newAction()
    {
        $this->page->setTitle('视频上传');

        return get_defined_vars();
    }

    public function uploadAction($req)
    {
        $validator = wei()->validate([
            'data' => $req,
            'rules' => [
                'name' => [],
                'categoryId' => [],
            ],
            'names' => [
                'name' => '视频标题',
                'categoryId' => '视频类型',
            ],
        ]);
        if (!$validator->isValid()) {
            return $this->err($validator->getFirstMessage());
        }

        // 1. 上传到服务器
        $upload = wei()->upload;
        $result = $upload([
            'name' => '视频',
            'exts' => ['mp4', 'mov'],
            'dir' => wei()->file->getUploadDir(),
            'fileName' => wei()->file->getUploadName(),
        ]);

        if (!$result) {
            return $this->err($upload->getFirstMessage());
        }

        // 2. 保存上传信息
        $req['file'] = $upload->getFile();
        $ret = wei()->file->upload($req['file']);
        if ($ret['fileId']) {
            $file = wei()->file()->curApp()->findOneById($ret['fileId']);
            $file->save([
                'categoryId' => $req['categoryId'],
                'startTime' => $req['startTime'],
                'endTime' => $req['endTime'],
                'type' => File::TYPE_VIDEO,
            ]);

            // 3. 保存视频信息
            $data = [
                'name' => $req['name'] ?: '',
                'description' => $req['description'] ?: '',
                'url' => $file['url'],
                'categoryId' => $req['categoryId'] ?: '',
                'enable' => 1,
                'type' => 2,
            ];

            wei()->video()->save($data);
        }

        return $this->ret($ret);
    }
}
