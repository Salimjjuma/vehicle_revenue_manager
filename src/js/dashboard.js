document.addEventListener('DOMContentLoaded', function () {
    // Get the current page URL
    const currentPage = window.location.href;

    // Get all sidebar links
    const sidebarLinks = document.querySelectorAll('.nav-item a');

    // Loop through each link and check if it matches the current page URL
    sidebarLinks.forEach(link => {
        const linkURL = link.href;

        if (currentPage === linkURL) {
            // Add the "active" class to the link if it matches the current page URL
            link.classList.add('active');
        } else if (currentPage.includes(linkURL) && linkURL !== currentPage) {
            // Add a class to style the link as a parent page
            link.classList.add('parent-page');
        }
    });
});

function goBack() {
    window.history.go(-1);
    return false;
}