<dialog id="changePW" title="Change password">
    <form>
        To change the password for <?=$name?>, please provide your current password as well as the desired new password.<br />
        <input name="name" type="hidden" value="<?=$name?>" /><br />
        <label>Old password: <input name="password" type="password" class="ui-widget-content ui-corner-all" /></label><br />
        <label>New password: <input name="pwnew" type="password" class="ui-widget-content ui-corner-all" /></label><br />
        <label>Repeat new password: <input name="pwnew" type="password" class="ui-widget-content ui-corner-all" /></label>
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
