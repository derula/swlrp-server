<section>
    <h2>Please Log In to Edit</h2>
    <form action="?" method="POST">
        <label>Password: <input name="password" type="password" class="ui-widget-content ui-corner-all" /></label><br />
<? foreach ($names as $key => $value): ?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<? endforeach ?>
        <input type="submit" value="Log in" />
    </form>
</section>
