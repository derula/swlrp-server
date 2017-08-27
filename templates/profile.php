<div id="container" <? if (static::EDIT_MODE_ENABLED === $this->getEditMode()): ?>class="editmode"<? endif ?>>
    <div id="tabs" class="tabs">
        <ul>
<? foreach ($structure as $section): ?>
            <li><a href="#tabs-<?=$section['name']?>"><?=$section['title']?></a></li>
<? endforeach ?>
        </ul>
<? foreach ($structure as $section): ?>
        <div id="tabs-<?=$section['name']?>" class="wrapper">
            <div class="column_one">
                <div class="column_text">
<? foreach ($section['texts'] as $prop): ?>
                    <div>
                        <h3><?=$prop['title']?></h3>
                        <p><?=$this->decorate($prop, 'text')?></p>
                    </div>
<? endforeach ?>
                </div>
            </div>
            <div class="column_two">
                <div class="column_text">
                    <div class="headshot">
                        <img class="portrait" src="<?=$portrait ?: '/assets/images/image_default.png'?>" />
                    </div>
<? foreach ($section['properties'] as $prop): ?>
                    <div class="field_title <?=$prop['name']?>"><?=$prop['title']?></div>
                    <div class="field_content"><?=$this->decorate($prop, 'property')?></div>
<? endforeach ?>
                </div>
            </div>
        </div>
<? endforeach ?>
    </div>
    <div id="editbuttons">
<? if (static::EDIT_MODE_ENABLED === $this->getEditMode()): ?>
        <button id="edit"></button>
<? elseif (static::EDIT_MODE_REQUESTED === $this->getEditMode()): ?>
        <button class="link" data-href="/front/<?=htmlspecialchars($this->getRequestString())?>">Log in</a>
<? endif ?>
    </div>
</div>
