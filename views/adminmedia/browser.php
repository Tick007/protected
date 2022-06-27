<?

$this->widget('application.extensions.elfinder.ElFinderWidget',array(
    'lang'=>'en',
    'url'=>CHtml::normalizeUrl(array('/adminmedia/fileManager')),
    'editorCallback'=>'js:function(url) {
        var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(d+).*$/, "$1");
        window.opener.CKEDITOR.tools.callFunction(funcNum, url);
        window.close();
    }',
))
?>