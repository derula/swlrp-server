<!DOCTYPE html>
<title><?=$title?></title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"
        integrity="sha256-KM512VNnjElC30ehFwehXjx1YCHPiQkOPmqnrWtpccM=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sceditor/1.5.2/jquery.sceditor.min.js"
        integrity="sha256-x8t1a+ZsyZIkhEME9b8rZaOtOACrV9fJDHn/n7ApkXk=" crossorigin="anonymous"></script>
<script src="<?=$this->assetUrl('script.js')?>"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/dark-hive/jquery-ui.css"
      integrity="sha256-430fmdsHlbyhcsmK+R+9wspVgGJBgjkWM5tuB2XC03U=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sceditor/1.5.2/themes/monocons.min.css"
      integrity="sha256-6DjgI077lS1q2rG+PzkxbKV4tMrOkhNla9DD5NF1hy8=" crossorigin="anonymous" />
<link rel="stylesheet" href="<?=$this->assetUrl('mod.css')?>" />
<main><?=$content?></main>
<?=$dialogs?>
