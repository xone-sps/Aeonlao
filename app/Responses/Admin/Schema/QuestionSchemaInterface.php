<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 5/22/2019
 * Time: 1:00 PM
 */

namespace App\Responses\Admin\Schema;


interface QuestionSchemaInterface
{
    public function singleContent();
    public function build($raw);
}
