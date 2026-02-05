document.addEventListener('DOMContentLoaded', function() {
    let showSidebar = true;
    const toggleButton = document.querySelector('.hamburger-btn');
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.main');

    if (toggleButton && sidebar && main) {
        toggleButton.addEventListener('click', function() {
            showSidebar = !showSidebar;
            if (showSidebar) {
                sidebar.classList.remove('w-0');
                sidebar.classList.add('w-64');
                main.classList.add('ml-64');
            } else {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-0');
                main.classList.remove('ml-64');
            }
        });
    }
});