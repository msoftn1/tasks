<?php require(__DIR__ . '/../header.php'); ?>

<?php if (Auth::check()) { ?>
    <div>
        <table class="table">
            <tr>
                <td>
                    Здравствуйте, <?=htmlspecialchars($user->fio, ENT_QUOTES, 'UTF-8');?> | <a href="<?=$url('admin/logout');?>">Выход</a>
                </td>
            </tr>
        </table>
    </div>
<?php } else {?>
    <button type="submit" class="btn btn-primary" onclick="window.location.href='<?=$url('admin');?>';">Авторизация</button>
<?php } ?>

<h1>Список задач</h1>

<table class="table">
    <tr>
        <th><a href="<?= $sortUrl('user_name'); ?>">имя пользователя</a></th>
        <th><a href="<?= $sortUrl('email'); ?>">email</a></th>
        <th style="max-width: 400px">текст задачи</th>
        <th><a href="<?= $sortUrl('is_completed'); ?>">статус</a></th>
        <?php if (Auth::check()) { ?><th>изменить статус</th><?php } ?>
    </tr>
    <?php foreach ($tasks['data'] as $task) { ?>
        <tr data-id="<?= $task->id; ?>">
            <td><?= $escape($task->userName); ?></td>
            <td><?= $escape($task->email); ?></td>
            <td style="max-width: 400px" class="text">
                <?= $escape($task->text); ?>
                <?php if (Auth::check()) { ?>
                <br/><a href="" data-id="<?= $task->id; ?>">Редактировать</a>
                <?php } ?>
                <?php if ($task->isEdited) { ?>
                <br/><i>отредактировано админстратором</i>
                <?php }?>
            </td>
            <td class="status"><?= ($task->isCompleted ? 'выполнено' : 'в работе'); ?></td>
            <?php if (Auth::check()) { ?><td><input type="checkbox" data-id="<?= $task->id; ?>" <?= ($task->isCompleted ? 'checked' : '0'); ?>/> выполнено</td><?php } ?>
        </tr>
    <?php } ?>
    <?php if (count($tasks['data']) == 0) { ?>
        <tr>
            <td colspan="5">Задачи не добавлены.</td>
        </tr>
    <?php } ?>
</table>
<div>
    <?php for ($i = 1; $i <= $tasks['pages']; $i++) { ?>
        <a href="<?= $pagerUrl($i); ?>"><?= $i; ?></a>&nbsp;
    <?php } ?>
</div>

<div>
    <br/>
    <br/>
    <br/>
    <a href="" id="addTask">Добавить задачу</a>
</div>

<div style="display: none" id="add-task-container">
    <hr/>
    <h2>Добавить задачу</h2>
    <form id="add-task-form" style="max-width: 400px;">
        <div id="message"></div>

        <div class="form-group">
            <label for="">Имя пользователя</label>
            <input type="text" class="form-control" name="user_name"/>
        </div>

        <div class="form-group">
            <label for="">E-mail</label>
            <input type="text" class="form-control" name="email"/>
        </div>

        <div class="form-group">
            <label for="">Текст задачи</label>
            <textarea name="text" class="form-control"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" class="form-control" value="Добавить"/>
        </div>
    </form>
</div>

<div class="modal" id="edit-text" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edit-task-form">
                <div class="modal-body">
                        <div id="edit-task-form-message"></div>
                        <input type="hidden" name="id" id="edit-task-form-id"/>
                        <div class="form-group">
                            <label for="">Текст задачи</label>
                            <textarea name="text" class="form-control" style="height: 300px;"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#addTask').click(function ()
    {
        $('#add-task-container').toggle();
        return false;
    });

    $('#add-task-form').submit(function () {
        $.post('<?=$url('add-task');?>', $('#add-task-form').serialize(), function (data) {
            if (!data.status) {
                $('#message').html(data.error);
            } else {
                alert('Задача успешно добавлена!');
                window.location.href = '/';
            }
        });

        return false;
    })

    var textNoAuthorization = 'Вы не авторизованы.';

    $('input[type=checkbox][data-id]').change(function() {
        var data = {
            'id': $(this).attr('data-id'),
            'is_completed': this.checked
        };

        var updateText = (this.checked) ? 'выполнено' : 'в работе';
        $('tr[data-id='+data.id+'] .status').text(updateText);

        $.post('<?=$url('change-status');?>', data, function (data) {
            alert('Статус задачи изменен!');
        }).fail(function() {
            alert(textNoAuthorization);
        });;
    });

    $('a[data-id]').click(function() {
        var id = $(this).attr('data-id');
        $.get('<?=$url('load-task');?>', {'id': id}, function (data) {
            $('#edit-task-form-id').val(id);
            $("#edit-text textarea").val(data.task.text);

            $('#edit-text').modal({});
        }).fail(function() {
            alert(textNoAuthorization);
        });

        return false;
    });

    $('#edit-task-form').submit(function() {
        $.post('<?=$url('save-task');?>', $('#edit-task-form').serialize(), function (data) {
            if (!data.status) {
                $('#edit-task-form-message').html(data.error);
            } else {
                alert('Задача успешно сохранена!');
                window.location.href = '/';
            }
        }).fail(function() {
            alert(textNoAuthorization);
        });;

        return false;
    })
</script>
<?php require(__DIR__ . '/../footer.php'); ?>
