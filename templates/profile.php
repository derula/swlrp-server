<section class="accordion">
<? foreach ($structure as $section): ?>
    <h1><?=$section['title']?></h1>
    <article>
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
