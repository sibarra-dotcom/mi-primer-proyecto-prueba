window.addEventListener('click', e => {
  let modal_active = document.querySelector('.modal_active');
  let form = modal_active?.querySelector('form');
  if (e.target === modal_active) {
    modal_active.classList.remove('modal_active');
    modal_active.classList.add('hidden');
    form?.reset();

    if(form.id == 'form_edit') {
      files_loaded.textContent = '';
      dragDropContainer.querySelector('span').innerHTML = 'Arrastre y suelte sus archivos para agregarlos.';
      fileList.innerHTML = '';
      archivo_files = [];
    } else if (form.id == 'form_add_comment') {
      addComment.classList.add('hidden');
    }
  }
}); 


const _alert = document.querySelector('#msg_alert');
if (_alert) {
  setTimeout(()=> {
    _alert.remove();
  }, 4000);
}