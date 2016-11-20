<?php

namespace Miaoxing\Video\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20161120164720CreateVideoTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->scheme->table('video')
            ->id()
            ->int('categoryId')
            ->string('url')
            ->string('vid', 32)
            ->string('name')
            ->string('pic')
            ->mediumText('description')
            ->bool('enable')
            ->tinyInt('type', 1)->defaults(1)->comment('1是外部链接，2是上传链接')
            ->timestamps()
            ->int('createUser')
            ->int('updateUser')
            ->timestamp('deleteTime')
            ->int('deleteUser')
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->scheme->dropIfExists('video');
    }
}
