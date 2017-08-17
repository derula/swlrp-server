<div id="container" <? if ($editMode): ?>class="editmode"<? endif ?>>
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
<? if ($editMode): ?>
    <div id="editbuttons">
        <button id="edit"></button>
    </div>
<? endif ?>
</div>
