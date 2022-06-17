<h2>Notes</h2>
<div class="feed-container">
    <table cellpadding="0" cellspacing="0" width="100%" > <!--- Inline styling, the borders and padding aren't working through stylesheet -->    
    <?php

    // Print all notes
    foreach($feed as $feed_item): if ( $feed_item['type'] !== 'log') : ?>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #000;">
                <strong><?php echo $feed_item['user'] ?></strong>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #000;">
                <?php echo $feed_item['content'] ?>
            </td>
            <td style="padding: 10px; border-bottom: 1px solid #000;">
                <?php echo $feed_item['time_ago'] ?? $feed_item['date'] ?>
            <?php
                if( isset( $feed_item[ 'note_type' ] ) ):
            ?>
                <div>
                    for <?php echo $feed_item['note_type'] ?>
                </div>
            <?php
                endif
            ?>
            </td>
        </tr>
    <?php endif; endforeach; ?>
    </table>    
</div>