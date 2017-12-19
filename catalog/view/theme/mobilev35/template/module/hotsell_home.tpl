<?php foreach($products as $result) {?>
					<li class="frist_tab_u1 ma18">
					<div class="img_u1"><a target="_blank"
						href="<?php echo $result['href']; ?>"> <img
						src="<?php echo $result['thumb']; ?>"></a></div>
					<p class="name"><a target="_blank"
						href="<?php echo $result['href']; ?>"
						title="<?php echo $result['name']; ?>"><?php echo $result['name']; ?></a></p>
						<?php if($result['special']) {?>
					<p class="price"><?php echo $this->currency->format($result['special']); ?><span><?php echo $this->currency->format($result['price']); ?></span></p>
					<?php } else {?>
					<p class="price"><?php echo $this->currency->format($result['price']); ?><span></span></p>
					<?php } ?>
					</li>
				<?php } ?>