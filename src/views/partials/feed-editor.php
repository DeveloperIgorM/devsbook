<div class="box feed-new">
  <div class="box-body">
    <div class="feed-new-editor m-10 row">
      <div class="feed-new-avatar">
        <img src="<?= $base; ?>/media/avatars/<?= $user->avatar; ?>" />
      </div>
      <div class="feed-new-input-placeholder">O que você está pensando, <?= $user->name; ?>?</div>
      <div class="feed-new-input" contenteditable="true"></div>
      <div class="feed-new-photo">
          <img src="<?=$base;?>/assets/images/photo.png"/>
          <input type="file" name="photo" class="feed-new-file" accept="image/pnj,image/jpg,image/jpeg" />
      </div>
      <div class="feed-new-send">
        <img src="<?=$base;?>/assets/images/send.png" />
      </div>

      <form class="feed-new-form" method="POST" action="<?= $base; ?>/post/new">
        <input type="hidden" name="body" />
      </form>

    </div>
  </div>
</div>

<script type="text/javaScript">
  let feedInput = document.querySelector('.feed-new-input');
  let feedSubmit = document.querySelector('.feed-new-send');
  let feedForm = document.querySelector('.feed-new-form');
  let feedPhoto = document.querySelector('.feed-new-photo');
  let feedFile = document.querySelector('.feed-new-file');

  feedPhoto.addEventListener('click', function() {
    feedFile.click();
  });

  feedFile.addEventListener('change', async function() {
    console.log("fotos", feedFile.files);
    let photo = feedFile.files[0];

    let formData = new FormData();
    formData.append('photo', photo);
    
    let req = await fetch(BASE+'/ajax/upload', {
      method:'POST',
      body: formData
    });

    let json = await req.json(); // AWAIT -> "Pare aqui até que essa operação termine, mas sem travar o resto do programa"

    // SE tiver erro
    if (json.error != '') {
      alert(json.error);
    }

    //atualiza a página
    window.location.href = window.location.href;
  });

feedSubmit.addEventListener('click', function(obj) {
  let value = feedInput.innerText.trim();

  if(value != '') {
    feedForm.querySelector('input[name=body]').value = value;
    feedForm.submit();
  }
});

</script>
<script>
const input = document.querySelector('.feed-new-input');
const placeholder = document.querySelector('.feed-new-input-placeholder');

placeholder.addEventListener('click', () => {
  placeholder.style.display = 'none';
  input.style.display = 'block';
  input.focus();
});
</script>
