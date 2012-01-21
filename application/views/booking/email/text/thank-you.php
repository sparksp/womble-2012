Dear <?php echo htmlspecialchars($group->contact); ?>,

This is an automated e-mail to confirm that you have registered <?php echo count($group->attendees); ?> attendees to attend Womble 2012.  You need to pay a £5 non-refundable deposit by 31st March 2012.  The remaining £30 per person is due by 1st June 2012.  Please make cheques payable to "Leicestershire Scout Council" and write "Womble #<?php echo $group->id; ?>" on the reverse.  Send your cheques to:

    Womble, County Scout Centre
    Winchester Road, Blaby
    Leicestershire
    LE8 4HN

Group: <?php echo htmlspecialchars($group->name); ?>
Contact: <?php echo htmlspecialchars($group->contact), ' <', htmlspecialchars($group->email), '>'; ?>

<?php foreach ($group->attendees as $attendee): ?>
Attendee <?php echo ++$a; ?>: <?php echo htmlspecialchars($attendee->name); ?>, <?php echo $attendee->saturday; ?>, <?php echo $attendee->sunday; ?>
<?php endforeach; ?>

Yours in Scouting,

The Womble Team
