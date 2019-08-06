<!--/ in header ---------------------------------->
<header class="navbar navbar-fixed-top">
	<div class="navbar-default">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-left">
					<?php
						foreach($cfg["menu"] as $i){
							$active = $i["module"] == $module ? "class='active'" : "";
							echo '<li '.$active.'> <a href="'.$apayus->url($i["url"]).'"> <span class="'.$i["ico"].'" style="margin:0 1 0 1;"></span> '.$i["label"].' </a></li>';
						}
					?>
                </ul>

				<ul class="nav navbar-nav navbar-right grond"> 
					<?php
						$active = "user" == $module ? " active " : "";
						echo '<li class="'.$active.'dropdown">';
					?>
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-user"></span> 
							<span><?php echo $name ?></span>
							<b class="caret"></b>
						</a>

						<ul class="dropdown-menu">
							<li><a href="<?php echo $apayus->url('user/edit'); ?>"><span class="glyphicon glyphicon-cog"> </span> <span> Mi p&aacute;gina </span> </a></li>
							<li><a href="<?php echo $apayus->url('user/logout');?>"><span class="glyphicon glyphicon-off"> </span> <span> Cerrar sesi&oacute;n </span> </a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
</header>
<!--/ end header ---------------------------------->