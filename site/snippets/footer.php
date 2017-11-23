</div>

<footer>
	<div><?= $site->footer()->kt() ?></div>
	<?php if($site->legal()->isNotEmpty()): ?>
		<?php if($legal = $site->legal()->toPage()): ?>
		<div><a href="<?= $legal->url() ?>" data-target="page"><?= $legal->title()->html() ?></a></div>
		<?php endif ?>
	<?php endif ?>
	<div>Website Design by <a href="http://www.vlf-studio.com" target="_blank">VLF</a></div>
</footer>

</div>

<?php if(!$site->googleanalytics()->empty()): ?>
  <!-- Google Analytics-->
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', '<?php echo $site->googleanalytics() ?>', 'auto');
    ga('send', 'pageview');
  </script>
<?php endif ?>
	<script>
		var $sitetitle = '<?= $site->title()->escape() ?>';
	</script>
	<?php
	echo js(array('assets/js/build/plugins.js?=v4', 'assets/js/build/app.min.js?=v4'));
	?>

</body>
</html>