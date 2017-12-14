<?/*
Шаблон просмотра задачи
=======================
*/?>

<h2 id="header"><?=$pageTitle;?></h2>
<div class="container">
	<div class="row">
		<div class="col-md-8 block">
			<dl class="dl-horizontal">
			  <dt>Имя</dt>
			  <dd><?=$task['name'];?></dd>
			  <dt>Email address</dt>
			  <dd><?=$task['email'];?></dd>
			  <dt>Текст задачи</dt>
			  <dd><?=$task['content'];?></dd>
			</dl>
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<?php if($task['isReady']):?>	
						<div class="alert alert-success" role="alert">Задача была выполнена<span class="glyphicon glyphicon-ok glyp"></span></div>
					<?php else:?>
						<div class="alert alert-danger" role="alert">Задача не выполнена<span class="glyphicon glyphicon-remove"></span></div>
					<?php endif;?>
					<?=$editing;?>
				</div>
			</div> 	
		</div>
		<div class="col-md-4">
			
			<img src="<?=$task['image'];?>" class="img-responsive" alt="Responsive image">
		</div>

	</div>
</div>
