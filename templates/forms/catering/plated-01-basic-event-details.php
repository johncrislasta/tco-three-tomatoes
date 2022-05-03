<h3>What is the occasion?</h3>
<select name="occasion" id="occasion-select">
    <option value="">Choose occasion</option>
    <?php foreach($occasions as $occasion): ?>
        <option value="<?php echo $occasion->slug ?>">
            <?php echo $occasion->name ?>
        </option>
    <?php endforeach; ?>
    <option value="other">Other</option>
</select>
<input name="occasion_other" id="occasion-other" class="other-input" type="text" placeholder="What's the occasion?"/>

<h3>How many guests are we expecting?</h3>
<input id="number-guest-input" name="number_guest_input" type="number" min="10" placeholder="10" required >

<h3>Where will the event be held?</h3>
<select name="venue" id="venue-select">
    <option value="">Choose venue</option>
    <?php foreach($venues as $venue): ?>
        <option value="<?php echo $venue->slug ?>">
            <?php echo $venue->name ?>
        </option>
    <?php endforeach; ?>
    <option value="other">Other</option>
</select>
<input name="venue_other" id="venue-other" class="other-input" type="text" placeholder="Where's the venue?"/>
