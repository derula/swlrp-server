<dialog id="changePW" title="Change password">
    <form>
        Enter the fields below to change the password for <?=$name?>.<br />
        <input name="name" type="hidden" value="<?=$name?>" /><br />
        <label>Old password: <input name="password" type="password" /></label><br />
        <label>New password: <input name="pwnew" type="password" /></label><br />
        <label>Repeat new password: <input name="pwnew" type="password" /></label>
    </form>
</dialog>
<dialog id="pwChanged" title="Success">
    The password for <?=$name?> has been changed.<br />
    Please use the new pasword from now on.
</dialog>
<dialog id="pwChangeFailed" title="Success">
    The password for <?=$name?> could not be changed.<br />
    Please make sure to enter the old password correctly.
</dialog>
<dialog id="changePortrait" title="Change portrait">
    <label>
        Please enter the URL for your character portrait. Recommended size: 200x200.
        Images larger than this will be resized to fit.<br />
        <input type="text" maxlength="255" />
    </label>
</dialog>
