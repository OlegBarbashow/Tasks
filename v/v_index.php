<?/*
Шаблон вывода списка задач
============================
*/?>

<div class="row">
	<div class="col-md-offset-10">
		<div class="btn-group">
		  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			Сортировка <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
			<li><a href="index.php?s=nameAZ">Имя A->Z</a></li>
			<li><a href="index.php?s=nameZA">Имя Z->A</a></li>
			<li class="divider"></li>
			<li><a href="index.php?s=mailAZ">Email A->Z</a></li>
			<li><a href="index.php?s=mailZA">Email Z->A</a></li>
			<li class="divider"></li>
			<li><a href="index.php?s=ready">Выполнено</a></li>
			<li><a href="index.php?s=notReady">Не выполнено</a></li>
		  </ul>
		</div>
	</div>
	<h2><?=$pageTitle;?></h2>
</div>
<ul class="list-group">
	<li class="list-group-item">
		<b><a href="index.php?c=new">Новая задача</a></b>
	</li>
	<?php if($tasks != null):?>
		<?php foreach ($tasks as $task): ?>
		
			<li class="list-group-item">
				<a href="index.php?c=view&id=<?=$task['id_task']?>">
					<?=$task['email']?>
				</a><br />
				<?=$task['intro']?>
			</li>
		<?php endforeach ?>
	<?php else:?>
		<p>Задач нет</p>
	<?php endif;?>
</ul>


<nav aria-label="Page navigation">
  <ul class="pagination">
	<?=$pervpage;?>
	<?php while($p<=$total):?>
		<?php if($p == $_GET['page']):?>
			<?='<li class="active"><a href= ./index.php?page='.$p.'>'.$p.'</a></li>';?>
		<?php else:?>
			<?='<li><a href= ./index.php?page='.$p.'>'.$p.'</a></li>';?>
		<?php endif;?>
			<?php $p++;?>
	<?php endwhile;?>
	  <?=$nextpage;?>
  </ul>
</nav>