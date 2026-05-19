const colorPicker = document.getElementById('colorPicker');
const colorPreview = document.getElementById('colorPreview');
const colorCode = document.getElementById('colorCode');

if(colorPicker){

    colorPreview.style.background = colorPicker.value;
    colorCode.innerText = colorPicker.value;

    colorPicker.addEventListener('input', function(){

        colorPreview.style.background = this.value;

        colorCode.innerText = this.value;

    });

}

/* AUTO SLUG */

const nameInput = document.getElementById('name');
const slugInput = document.getElementById('slug');

if(nameInput){

    nameInput.addEventListener('keyup', function(){

        let slug = this.value
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "")
            .replace(/đ/g, "d")
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '');

        slugInput.value = slug;

    });

}