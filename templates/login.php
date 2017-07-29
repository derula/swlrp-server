<div id="container" class="single_column">
    <div class="wrapper">
        <div class="column_one">
            <div class="column_text">
                <h2>Please Log In to Edit</h2>
                <form action="?" method="POST">
                    <label>Password: <input name="password" type="password" class="ui-widget-content ui-corner-all" /></label><br />
<? foreach ($names as $key => $value): ?>
                    <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<? endforeach ?>
                    <input type="submit" value="Log in" />
                </form>
            </div>
        </div>
    </div>
</div>
