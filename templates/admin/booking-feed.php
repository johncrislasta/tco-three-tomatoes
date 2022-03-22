<h2>Feed</h2>
<div class="feed-container">
    <?php
    // Print all notes
    foreach($feed as $feed_item): ?>
        <div class="feed-item <?php echo $feed_item['type'] ?>">

            <div class="user-avatar">
                <?php echo $feed_item['avatar'] ?>
            </div>

            <div class="feed-item__user-name">
                <?php echo $feed_item['user'] ?>
            </div>

            <div class="feed-item__content">
                <?php echo $feed_item['content'] ?>
            </div
            >
            <div class="feed-item__date" title="<?php echo $feed_item['date'] ?>">
                <?php echo $feed_item['time_ago'] ?? $feed_item['date'] ?>
            <?php
                if( isset( $feed_item[ 'note_type' ] ) ):
            ?>
                <div class="feed-item__note-type">
                    for <?php echo $feed_item['note_type'] ?>
                </div>
            <?php
                endif
            ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>