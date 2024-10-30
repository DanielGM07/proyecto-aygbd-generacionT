<?php if (isset($accion) && $accion) { ?>
  <div id="toast" class="fixed bottom-0 left-1/2 -translate-x-1/2 mb-8 opacity-100" style="transition: opacity 200ms ease 0s">
    <span class="max-w-[30rem] py-3 px-6 flex rounded-full bg-green-600/85 text-white">
      <p><?php echo $accion; ?></p>
      <button type="button" class="material-symbols-outlined text-lg h-max ml-3" onclick="closeToast()">
        close
      </button>
      <div class="opacity-0"></div>
    </span>
  </div>
  <script>
  const toast = document.getElementById('toast')

  setTimeout(() => {
    toast.style.opacity = 0
    setTimeout(() => {
      toast.hidden = 'hidden'
    }, 200)
  }, 3800)

  function closeToast() {
    toast.style.opacity = 0;
    setTimeout(() => {
      toast.hidden = 'hidden'
    }, 200)
  }
  </script>
<?php } ?>