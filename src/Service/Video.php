<?php

namespace Miaoxing\Video\Service;

class Video extends \miaoxing\plugin\BaseModel
{
    public function getPic($vid)
    {
        $tot = 0;
        $len = strlen($vid);
        for ($i = 0; $i < $len; ++$i) {
            $tot = ($tot << 5) + $tot + $vid[$i];
        }
        $path = $tot % (10000 * 10000);

        return "http://vpic.video.qq.com/{$path}/{$vid}.jpg";
    }

    public function getCategoryToOptions()
    {
        $categories = wei()->category()->notDeleted()->withParent('video')->findAll();
        $html = '';
        foreach ($categories as $category) {
            $html .= '<option value="' . $category['id'] . '" >' . $category['name'] . '</option>';
        }

        return $html;
    }
}
