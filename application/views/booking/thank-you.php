<h1>Thank-you!</h1>

<p>Thank-you for your booking.  You have registered <?php echo count($group->attendees); ?> attendees to attend Womble 2012.  <strong>You need to pay a £5 non-refundable deposit per person by 29th February 2012.</strong>  The remaining £30 per person is due by 1st June 2012.  Please make cheques payable to "Leicestershire Scout Council" and write "Womble #<?php echo $group->id; ?>" on the reverse.  Send your cheques to:</p>

<p><address>
    Womble, County Scout Centre,<br />
    Winchester Road, Blaby,<br />
    Leicestershire<br />
    LE8 4HN<br />
</address></p>

<p>You will receive an automated e-mail shortly confirming this information.</p>

<h2>Details</h2>

<p><strong>Group:</strong> <?php echo htmlspecialchars($group->name); ?></p>
<p><strong>Contact:</strong> <?php echo htmlspecialchars($group->contact.' <'.$group->email.'>'); ?></p>

<?php foreach ($group->attendees as $attendee): ?>
<p><strong>Attendee <?php echo ++$a; ?>:</strong> <?php echo htmlspecialchars($attendee->name); ?>, <?php echo ucwords($attendee->saturday); ?> Saturday, <?php echo ucwords($attendee->sunday); ?> Sunday</p>
<?php endforeach; ?>
