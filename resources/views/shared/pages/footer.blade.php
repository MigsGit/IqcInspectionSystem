<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
        <b id="footerTimer"></b>
    </div>
    <strong>IIS</strong>&nbsp;<small>Ver. 1.0</small>
</footer>

<script type="text/javascript">
	setInterval( () => {
		var now = new Date();
		$("#footerTimer").text(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
	}, 1000);
</script>
