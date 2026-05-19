const subjectSelect = document.getElementById('subjectSelect');
const orderInput = document.getElementById('orderInput');

function updateOrder(){

    const selectedOption =
        subjectSelect.options[subjectSelect.selectedIndex];

    const nextOrder =
        selectedOption.dataset.nextOrder || 1;

    orderInput.value = nextOrder;
}

if(subjectSelect){

    updateOrder();

    subjectSelect.addEventListener('change', updateOrder);

}