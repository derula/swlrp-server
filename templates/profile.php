<? foreach ($structure as $section): ?>
    <h1><?=$section['title']?></h1>
    <dl>
    <? foreach ($section['properties'] as $prop): ?>
        <dt><?=$prop['title']?></dt><dd><?=$this->decorate($prop, 'property')?></dd>
    <? endforeach ?>
    </dl>
    <? foreach ($section['texts'] as $prop): ?>
    <?=$prop['title']?>
    <p><?=$this->decorate($prop, 'text')?></p>
    <? endforeach ?>
<? endforeach ?>
<? if ($editMode): ?>
    <button id="edit">Change profile</button>
<? endif ?>
