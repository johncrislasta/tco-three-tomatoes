<h2>Private Notes</h2>
<ul class="notes private">
    <?php
    // Print all notes
    foreach($private_notes as $note): ?>
        <li>
<!--            <span class="user-avatar">--><?php //echo $note['avatar'] ?><!--</span>-->
            <strong class="user-name">
                <?php echo $note['name'] ?>
            </strong> commented in private
            <span class="date" title="<?php echo $note['date_sent'] ?>">
            <?php echo $note['time_ago'] ?>
            </span>
            <span class="type">
            for <?php echo $note['type'] ?>
            </span>
            <pre class="message"><?php echo $note['message'] ?></pre>
        </li>
    <?php endforeach; ?>
</ul>
<form class="note_create" method="post">
    <input type="hidden" name="note_visibility" class="note_visibility" value="private"/>
    <textarea name="note_message" class="note_message" placeholder="Enter message"></textarea>
    <select name="note_type" class="note_type">
        <option>Admin</option>
        <option>Kitchen</option>
        <option>On Site</option>
    </select>
    <input type="submit" class="note_submit" name="note_submit" value="Send">
</form>