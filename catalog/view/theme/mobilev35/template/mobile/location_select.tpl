<?php echo $header; ?>
<header class="bar bar-header bar-positive rel">
	<h1 class="title aside-left">自提点选择</h1>
</header>
<div id="content" class="content">
<div id="dl-menu" class="dl-menuwrapper" data-url="index.php?route=point/home/initdata">
	<ul class="dl-menu dl-menuopen lists">

<?php foreach($cities as $city)  { ?>
        <li>
            <a href="#" class="item"><?php echo $city['name']; ?></a>

            <?php if($city['cbds']) { ?>
            <ul class="dl-submenu lists">
                <?php foreach($city['cbds'] as $cbd)  { ?>
                    <li><a href="#" class="item"><?php echo $cbd['name']; ?></a></li>
                <?php } ?>
            </ul>

            <?php } ?>

        </li>
<?php }  ?>
            <!--
			<li>
				<a href="#" class="item">Fashion</a>
				<ul class="dl-submenu lists">
					<li>
						<a href="#" class="item">Men</a>
						<ul class="dl-submenu">
                            <li><a href="#">Shirts</a></li>
							<li><a href="#">Jackets</a></li>
							<li><a href="#">Chinos &amp; Trousers</a></li>
							<li><a href="#">Jeans</a></li>
							<li><a href="#">T-Shirts</a></li>
							<li><a href="#">Underwear</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Women</a>
						<ul class="dl-submenu">
							<li><a href="#">Jackets</a></li>
							<li><a href="#">Knits</a></li>
							<li><a href="#">Jeans</a></li>
							<li><a href="#">Dresses</a></li>
							<li><a href="#">Blouses</a></li>
							<li><a href="#">T-Shirts</a></li>
							<li><a href="#">Underwear</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Children</a>
						<ul class="dl-submenu">
							<li><a href="#">Boys</a></li>
							<li><a href="#">Girls</a></li>
						</ul>
					</li>
				</ul>
			</li>
			<li>
				<a href="#">Electronics</a>
				<ul class="dl-submenu">
					<li><a href="#">Camera &amp; Photo</a></li>
					<li><a href="#">TV &amp; Home Cinema</a></li>
					<li><a href="#">Phones</a></li>
					<li><a href="#">PC &amp; Video Games</a></li>
				</ul>
			</li>
        <li>
				<a href="#">Furniture</a>
				<ul class="dl-submenu">
					<li>
						<a href="#">Living Room</a>
						<ul class="dl-submenu">
							<li><a href="#">Sofas &amp; Loveseats</a></li>
							<li><a href="#">Coffee &amp; Accent Tables</a></li>
							<li><a href="#">Chairs &amp; Recliners</a></li>
							<li><a href="#">Bookshelves</a></li>
						</ul>
					</li>
					<li>
						<a href="#">Bedroom</a>
						<ul class="dl-submenu">
							<li>
								<a href="#">Beds</a>
								<ul class="dl-submenu">
									<li><a href="#">Upholstered Beds</a></li>
									<li><a href="#">Divans</a></li>
									<li><a href="#">Metal Beds</a></li>
									<li><a href="#">Storage Beds</a></li>
									<li><a href="#">Wooden Beds</a></li>
									<li><a href="#">Children's Beds</a></li>
								</ul>
							</li>
							<li><a href="#">Bedroom Sets</a></li>
							<li><a href="#">Chests &amp; Dressers</a></li>
						</ul>
					</li>
					<li><a href="#">Home Office</a></li>
					<li><a href="#">Dining &amp; Bar</a></li>
					<li><a href="#">Patio</a></li>
				</ul>
			</li>
			<li>
				<a href="#">Jewelry &amp; Watches</a>
				<ul class="dl-submenu">
					<li><a href="#">Fine Jewelry</a></li>
					<li><a href="#">Fashion Jewelry</a></li>
					<li><a href="#">Watches</a></li>
					<li>
						<a href="#">Wedding Jewelry</a>
						<ul class="dl-submenu">
							<li><a href="#">Engagement Rings</a></li>
							<li><a href="#">Bridal Sets</a></li>
							<li><a href="#">Women's Wedding Bands</a></li>
							<li><a href="#">Men's Wedding Bands</a></li>
						</ul>
					</li>
				</ul>
			</li>-->
		</ul>
	</div>
</div>

<link rel="stylesheet" type="text/css" href="js/dlmenu/css/component.css" />
<!--  <script type="text/javascript" src="catalog/view/theme/mobile/javascript/jquery-1.7.2.min.js"></script>-->
<script type="text/javascript" src="js/dlmenu/jquery.dlmenu.js"></script>

<!--
<script type="text/javascript" src="js/lodash-2.4.1.compat.min.js"></script>
-->

<script type="text/javascript">
$(function() {
	$( '#dl-menu').dlmenu();
});
</script>
<?php echo $footer; ?>