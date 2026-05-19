const menuToggle = document.getElementById('menuToggle');
const sidebar = document.getElementById('sidebar');

if(menuToggle){
    menuToggle.addEventListener('click', () => {
        sidebar.classList.toggle('show');
    });
}