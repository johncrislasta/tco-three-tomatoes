<h2>Notes</h2>
<ul class="notes">
    <?php
    // Print all notes
    foreach($notes as $note): ?>
    <li>
<!--        <span class="user-avatar">--><?php //echo $note['avatar'] ?><!--</span>-->
        <strong class="user-name">
            <?php echo $note['name'] ?>
        </strong> commented
        <span class="date" title="<?php echo $note['date_sent'] ?>">
            <?php echo $note['time_ago'] ?>
        </span>
        <pre class="message"><?php echo $note['message'] ?></pre>
    </li>
    <?php endforeach; ?>
</ul>
<form class="note_create" method="post">
    <input type="hidden" name="note_visibility" class="note_visibility" value="public"/>
    <textarea name="" class="note_message" name="note_message" placeholder="Enter message"></textarea>
    <input type="submit" class="note_submit" name="note_submit" value="Send">
</form>