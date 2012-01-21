<html><body>

<p>Dear <?php echo htmlspecialchars($group->contact); ?>,</p>

<p>This is an automated e-mail to confirm that you have registered <?php echo count($group->attendees); ?> attendees to attend Womble 2012.  <strong>You need to pay a £5 non-refundable deposit per person by 31st March 2012.</strong>  The remaining £30 per person is due by 1st June 2012.  Please make cheques payable to "Leicestershire Scout Council" and write "Womble #<?php echo $group->id; ?>" on the reverse.  Send your cheques to:</p>

<p><address>
    Womble, County Scout Centre,<br />
    Winchester Road, Blaby,<br />
    Leicestershire<br />
    LE8 4HN<br />
</address></p>

<p><strong>Group:</strong> <?php echo htmlspecialchars($group->name); ?></p>
<p><strong>Contact:</strong> <?php echo htmlspecialchars($group->contact), ' <', htmlspecialchars($group->email), '>'; ?></p>

<?php foreach ($group->attendees as $attendee): ?>
<p><strong>Attendee <?php echo ++$a; ?>:</strong> <?php echo htmlspecialchars($attendee->name); ?>, <?php echo $attendee->saturday; ?>, <?php echo $attendee->sunday; ?></p>
<?php endforeach; ?>

<p>Yours in Scouting,</p>

<p>The Womble Team</p>

</body></html>