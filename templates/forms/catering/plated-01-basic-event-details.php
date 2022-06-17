<div id="event-name-wrapper" class="plated-meals-input-wrapper">
    <h3>What shall we call this event?</h3>
    <input id="event-name-input" name="event_name" type="text" placeholder="Give this event a name" required >
</div>
<div id="occasion-wrapper" class="plated-meals-input-wrapper">
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
</div>
<div id="event-theme-input-wrapper" class="plated-meals-input-wrapper">
    <h3>What is the theme for this event?</h3>
    <input id="event-theme-input" name="event_theme" type="text" placeholder="e.g. Back in time, Garden party, Slumber Party" required >
</div>
<div id="number-guest-input-wrapper" class="plated-meals-input-wrapper">
    <h3>How many guests are we expecting?</h3>
    <input id="number-guest-input" name="number_of_guests" type="number" min="10" placeholder="10" required >
</div>
<div id="venue-select-wrapper" class="plated-meals-input-wrapper">
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
</div>
<div id="venue-contact-person-input-wrapper" class="plated-meals-input-wrapper">
    <h3>Who is the person in charge at this venue?</h3>
    <input id="venue-contact-person-input" name="venue_contact_person" type="text" placeholder="Name of the person to contact at the venue" required >
</div>
<div id="venue-contact-name-input" class="plated-meals-input-wrapper">
    <h3>What is the contact number of that person?</h3>
    <input id="venue-contact-name-input" name="venue_contact_number" type="text" placeholder="Give the contact number of that venue representative" required >
</div>