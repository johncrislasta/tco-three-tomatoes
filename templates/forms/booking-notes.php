<form class="note_create" method="post">
<!--    <input type="hidden" name="note_visibility" class="note_visibility" value="private"/>-->
    <textarea name="note_message" class="note_message" placeholder="Enter message"></textarea>
    <label>
        <input type="checkbox" value="private" name="note_visibility" class="note_visibility">
        Private
        <select name="note_type" class="note_type">
            <option value="Admin">for Admin</option>
            <option value="Kitchen">for Kitchen</option>
            <option value="On Site">for On Site</option>
        </select>
    </label>
    <input type="submit" class="note_submit" name="note_submit" value="Send"><input type="button" name="printnote" value="Print">
</form>