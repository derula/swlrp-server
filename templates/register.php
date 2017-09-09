<section>
    <h2>Register Your Password</h2>
    <form action="?" method="POST">
        <p>
            Please choose a password to secure your profile.<br />
            You can leave this empty, but it is not recommended.<br />
            If you decide not to enter a password, anyone can edit your profile!
        </p>
        <label>Password: <input name="password" type="password" class="ui-widget-content ui-corner-all" /></label><br />
        <label>Repeat password: <input name="password" type="password" class="ui-widget-content ui-corner-all" /></label><br />
<? foreach ($names as $key => $value): ?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<? endforeach ?>
        <input type="submit" value="Set password" />
    </form>
</section>
