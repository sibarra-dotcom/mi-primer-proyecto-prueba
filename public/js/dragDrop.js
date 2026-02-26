const fileStorage = new Map();

const initializeDragDropArea = (id) => {
  const dropArea = document.querySelector(`div[data-id="${id}"].drop-area`);
  if (!dropArea) return; 

	const fileInput = dropArea.querySelector('input[type="file"]');
	const dragText = dropArea.querySelector('span');
  const fileList = dropArea.querySelector('ul');

  let archivo_files = fileStorage.get(id) || []; 
  fileStorage.set(id, archivo_files);

  dropArea.addEventListener('dragover', (event) => {
    event.preventDefault();
    dropArea.classList.add('dragover');
  });

  dropArea.addEventListener('dragleave', () => {
    dropArea.classList.remove('dragover');
  });

  dropArea.addEventListener('drop', (event) => {
    event.preventDefault();
    dropArea.classList.remove('dragover');
    handleFiles(event.dataTransfer.files);
  });


  const renderFileList = () => {
    fileList.innerHTML = ''; 
		dragText.style.display = archivo_files.length === 0 ? 'block' : 'none';

    archivo_files.forEach((file, index) => {
      const fileItem = document.createElement('li');
      fileItem.innerHTML = `<span>${(file.size / 1024).toFixed(2)} KB</span> <span>${trimFileName(file.name)}</span>`;

      const deleteBtn = document.createElement('button');
      deleteBtn.type = 'button';
      deleteBtn.className = 'delete-btn';
      deleteBtn.innerHTML = '&times;';

      deleteBtn.addEventListener('click', () => {
        archivo_files.splice(index, 1); 
        renderFileList(); 
      });

      fileItem.appendChild(deleteBtn);
      fileList.appendChild(fileItem);
    });
  };

  // Handle files added by drag-drop or file input
  const handleFiles = (files) => {
    if (files.length + archivo_files.length > 10) {
      alert('You can upload a maximum of 10 files.');
      return;
    }

    for (let i = 0; i < files.length; i++) {
      archivo_files.push(files[i]);
    }

    renderFileList();
  };

  fileInput.addEventListener('change', () => handleFiles(fileInput.files));

  const btnOpen = dropArea.closest('.modal-drag-drop-wrapper').querySelector('.btn_file_click');
  if (btnOpen) {
    btnOpen.addEventListener('click', () => {
      fileInput.click(); 
    });
  }
};

const trimFileName = (fileName, maxLength = 18) => {
	if (fileName.length <= maxLength) {
		return fileName;
	}

	const extension = fileName.substring(fileName.lastIndexOf('.'));
	const baseName = fileName.substring(0, maxLength - extension.length);
	return `${baseName}...`;
};

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.drop-area').forEach(area => {
		console.log(area.dataset.id)
		initializeDragDropArea(area.dataset.id); 
	});
});