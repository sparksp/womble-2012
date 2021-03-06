		</section>
	</div>

	<footer class="footer container" id="bottom">
		<p>
			<span>&copy; <a href="http://www.leicestershirescouts.org.uk">Leicestershire Scout Council</a>.</span>

			<span>Made by <a href="http://phills.me.uk">Phill Sparks</a>.</span>
			<span>Powered by <a href="http://laravel.com">Laravel</a>.</span>
		</p>
		<p class="pull-right"><a href="#">Back to top</a>.</p>
<?php if (count($_COOKIE)): ?>
		<p class="pull-right"><?php echo HTML::link('cookies', 'Read about how we use cookies'); ?>.</p>
<?php endif; ?>
	</footer>

	<?php echo Asset::container('footer')->scripts(); ?>

	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-28331810-1']);
	  _gaq.push(['_trackPageview']);

	  (function() {
	    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>

</body>
</html>
