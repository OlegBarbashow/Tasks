<?/*
Шаблон новой задачи
===================
*/?>

<h2><?=$pageTitle;?></h2>
<form method='post' enctype="multipart/form-data">
  <div class="form-group">
	<label for="exampleInputPassword1">Имя</label>
	<input type="text" name="name" class="form-control" id="exampleInputPassword1">
  </div>
  <div class="form-group">
	<label for="exampleInputEmail1">Email address</label>
	<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
  </div>
  <div class="form-group">
    <label for="content">Текст задачи</label>
	<textarea id="content" name="content" class="form-control" rows="3"></textarea>
  </div>
  <div class="form-group">
	<label for="exampleInputFile">Выберете картинку</label>
	<input type="file" id="files" name="file" multiple>
  </div>
  <button type="reset" class="btn btn-default">Сброс</button>
  <button type="button" id="mypreview" class="btn btn-default">Предварительный просмотр</button>
  <button type="submit" class="btn btn-default">Добавить</button>
</form>
<?php if(!empty($this->errors)):?>
		<?php foreach($this->errors as $error):?>
			<div class="alert alert-danger" role="alert"><strong>Ошибка!</strong><br><?=$error;?></div>
		<?php endforeach;?>
<?php endif;?>

	<div id="myForm" hidden class="container">
		<div class="row">
			<div class="col-md-8 block">
				<dl class="dl-horizontal">
				  <dt>Имя</dt>
				  <dd id="name"></dd>
				  <dt>Email address</dt>
				  <dd id="email"></dd>
				  <dt>Текст задачи</dt>
				  <dd id="text"></dd>
				</dl>
				
				<div class="col-md-4 col-md-offset-4">
					<div class="alert alert-danger" role="alert">Задача не выполнена<span class="glyphicon glyphicon-remove"></span></div>
				</div>
			</div> 
			<div class="col-md-4">
				<output id="list"></output>
			</div>
		</div>
	</div>
</div>
