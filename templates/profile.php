<section class="tabs">
    <ul>
<? foreach ($structure as $section): ?>
        <li><a href="#<?=$section['name']?>"><?=$section['title']?></a></li>
<? endforeach ?>
    </ul>
<? foreach ($structure as $section): ?>
    <article id="<?=$section['name']?>">
        <dl>
        <? foreach ($section['properties'] as $prop): ?>
            <dt><?=$prop['title']?></dt><dd><?=$this->decorate($prop, 'property')?></dd>
        <? endforeach ?>
        </dl>
        <? foreach ($section['texts'] as $prop): ?>
        <?=$prop['title']?>
        <p><?=$this->decorate($prop, 'text')?></p>
        <? endforeach ?>
    </article>
<? endforeach ?>
</section>
<? if ($editMode): ?>
<footer>
    <hr />
    <button id="edit"></button>
</footer>
<?=$this->renderTemplate('editorDialogs', ['name' => $this->getRequestedName()])?>
<? endif ?>
