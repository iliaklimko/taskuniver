<?php

namespace backend\components\widgets\FileInput;

use kartik\file\FileInput;

class FileInputWidget extends FileInput
{
    public $pluginEvents = [
        'fileclear' => <<<JS
function (e) {
    var mClass = $(e.target).data('model-class'),
        mId = $(e.target).data('model-id')
        mAttribute = $(e.target).data('model-attribute');
    console.log(mClass);
    console.log(mId);
    console.log(mAttribute);
    $.post('/admin/file/clear', {
        modelClass: mClass, modelId: mId, modelAttribute: mAttribute
    }).done(function () {
        console.log('file cleared');
    });
}
JS
    ];
}
