<?php ?>

<button class="ring-2 ring-red">item</button>

<button data-modal="modal_files" class="btn_open_modal w-fit rounded border-2 text-pendiente border-pendiente text-opacity-0" type="button">Button PENDIENTE</button>
<button data-modal="modal_files" class="btn_open_modal w-fit rounded border-2 text-aprobado border-aprobado" type="button">Button APROBADO</button>
<button data-modal="modal_files" class="btn_open_modal w-fit rounded border-2 text-rechazado border-rechazado" type="button">Button NO APROBADO</button>


<script>

const setArticleStatus = (status) => {
  switch(status) {
    case "PENDIENTE":
    return {'color': 'text-pendiente border-pendiente text-opacity-0 ', 'icon': '<i class="fa fa-minus px-1"></i>'};
    break;
    case "APROBADO":
    return {'color': 'text-aprobado border-aprobado', 'icon': '<i class="fa fa-check px-1"></i>'};
    break;
    case "NO APROBADO":
    return {'color': 'text-rechazado border-rechazado', 'icon': '<i class="fa fa-minus px-1"></i>'};
    break;
  }
}

</script>