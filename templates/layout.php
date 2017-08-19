<!DOCTYPE html>
<title><?=$title?></title>
<script src="/components/jquery/jquery.min.js"></script>
<script src="/components/jquery-ui/jquery-ui.min.js"></script>
<script src="/components/sceditor/minified/jquery.sceditor.min.js"></script>
<script src="<?=$this->assetUrl('script.js')?>"></script>
<link rel="stylesheet" href="/components/jquery-ui/themes/dark-hive/jquery-ui.css" />
<link rel="stylesheet" href="/components/sceditor/minified/themes/monocons.min.css" />
<link rel="stylesheet" href="<?=$this->assetUrl('mod.css')?>" />
<section id="main"><?=$content?></section>
<?=$dialogs?>
