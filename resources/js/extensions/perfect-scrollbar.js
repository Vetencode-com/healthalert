// Import Perfect Scrollbar and its CSS
import PerfectScrollbar from "perfect-scrollbar";

// Wait until the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
    // Select all elements with the "perfect-scrollbar" class
    const scrollableElements = document.querySelectorAll(".perfect-scrollbar");

    // Initialize Perfect Scrollbar on each element
    scrollableElements.forEach((element) => {
        new PerfectScrollbar(element);
    });
});
