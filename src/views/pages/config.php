<?= $render('header', ['loggedUser' => $loggedUser]); ?>
<section class="container main">
  <?= $render('sidebar', ['activeMenu' => 'config']); ?>

  <section class="feed mt-10">

    <div class="row">
      <div class="column pr-5">

        <div class="page-title mt-10">
          <h1>Configurações</h1>
        </div>

        <form method="POST" action="<?= $base; ?>/config">
          <div class="update-avatar mt-10">
            <label for="avatar">Novo <span>Avatar:</span><br><br>
            </label>
            <input type="file" name="avatar" id="avatar" accept="image/*">
          </div>
          <div class="update-cover mt-10">
            <label for="capa">Nova <span>Capa:</span><br><br>
            </label>
            <input type="file" name="cover" id="cover" accept="image/*">
          </div>
          <br>
          <hr>

          <div class="container-edit">
            <div class="title mt-10">
              <label for="name">Nome Completo:</label><br>
              <input class="input" type="text" name="name" />
            </div>

            <div class="title mt-10">
              <label for="name">Data de Nascimento:</label><br>
              <input class="input" type="date" name="birthdate" />
            </div>

            <div class="title mt-10">
              <label for="name">E-mail:</label><br>
              <input class="input" type="email" name="email" />
            </div>

            <div class="title mt-10">
              <label for="name">Cidade:</label><br>
              <input class="input" type="text" name="city" />
            </div>

            <div class="title mt-10">
              <label for="work">Trabalho:</label><br>
              <input class="input" type="text" name="work" />
            </div>
            <br>
            <hr>

            <div class="title mt-10">
              <label for="password">Nova Senha:</label><br>
              <input class="input" type="password" name="password" />
            </div>

            <div class="title mt-10">
              <label for="password">Confirmar Nova Senha:</label><br>
              <input class="input" type="password" name="password_confirm" />
            </div>

            <div class="title mt-10">
              <input class="button" type="submit" value="salvar" />
            </div>
          </div>
        </form>
      </div>
    </div>

  </section>
</section>
<?= $render('footer'); ?>