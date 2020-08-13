<?php require(__DIR__ . '/../header.php'); ?>
    <div>
        <form id="auth-form" style="max-width: 400px">
            <h1>Авторизация</h1>
            <div id="message"></div>

            <div class="form-group">
                <label for="">Логин</label>
                <input type="text" class="form-control" name="login"/>
            </div>

            <div class="form-group">
                <label for="">Пароль</label>
                <input type="password" class="form-control" name="password"/>
            </div>

            <div class="form-group">
                <input type="submit" name="auth" class="form-control" value="Войти"/>
            </div>
        </form>
        <a href="<?=$url('');?>">К списку задач</a>
    </div>

    <script>
        $('#auth-form').submit(function() {
            $.post('<?=$url('admin/auth');?>', $('#auth-form').serialize(), function(data){
                if(!data.status) {
                    $('#message').html(data.error);
                }
                else {
                    alert('Вы успешно авторизованы!');
                    window.location.href = '<?=$url('');?>';
                }
            });

            return false;
        })
    </script>
<?php require(__DIR__ . '/../footer.php'); ?>
