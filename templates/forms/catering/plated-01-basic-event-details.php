<h3>What is the occasion?</h3>
<select>
    <option value="">Choose occasion</option>
    <?php foreach($occasions as $occasion): ?>
        <option value="<?php echo $occasion->slug ?>">
            <?php echo $occasion->name ?>
        </option>
    <?php endforeach; ?>
</select>


<h3>How many guests are we expecting?</h3>
<input id="number-guest-input" name="number_guest_input" type="number" min="10" placeholder="10" required >

<h3>Where will the event be held?</h3>
<input id="number-guest-input" name="number_guest_input" type="number" min="10" placeholder="10" required >

