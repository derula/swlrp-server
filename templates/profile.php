<nav class="tabs">
    <ul>
<? foreach ($structure as $section): ?>
        <li><a href="#tab-<?=$section['name']?>"><?=$section['title']?></a></li>
<? endforeach ?>
    </ul>
</nav>
<? foreach ($structure as $section): ?>
<div id="tab-<?=$section['name']?>" <? if (static::EDIT_MODE_DISABLED !== $this->getEditMode()): ?>class="editmode"<? endif ?>>
<? if (!empty($section['properties'])): ?>
    <aside>
        <img class="portrait" src="<?=$portrait ?: '/assets/images/image_default.png'?>" />
<? foreach ($section['properties'] as $prop): ?>
        <?=$this->decorate($prop, 'property')?>
<? endforeach ?>
    </aside>
<? endif ?>
    <section>
<? foreach ($section['texts'] as $prop): ?>
        <h3><?=$prop['title']?></h3>
        <article><?=$this->decorate($prop, 'text')?></article>
<? endforeach ?>
    </section>
</div>
<? endforeach ?>
<? if (static::EDIT_MODE_ENABLED === $this->getEditMode()): ?>
<button id="edit"></button>
<? elseif (static::EDIT_MODE_REQUESTED === $this->getEditMode()): ?>
<button id="edit" class="link" data-href="/front/<?=htmlspecialchars($this->getRequestString())?>">Log in</a>
<? endif ?>
