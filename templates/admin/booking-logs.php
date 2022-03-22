<h2>Logs</h2>
<ul class="logs">
    <?php
    // Print all notes
    foreach($logs as $log): ?>
        <li>
            <!--        <span class="user-avatar">--><?php //echo $note['avatar'] ?><!--</span>-->
            <strong class="user-name">
                <?php echo $log->get_user_name() ?>
            </strong>
            <span>
                <?php echo $log->text ?>
            </span>
            <span class="date">
                <?php echo $log->get_date_created() ?>
            </span>
        </li>
    <?php endforeach; ?>
</ul>