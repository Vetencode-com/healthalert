$(document).ready(function () {
    // Define your default values here
    const defaultValues = {
        '[name="name"]': "Wildan Testing",
        '[name="email"]': "wildanmzaki984@gmail.com",
        // '[name="email"]': "wildanmzaki7@gmail.com",
        // '[name="phone"]': "6281953112559",
        // '[name="phone"]': "628123",
        '[name="phone"]': "6289619925691",
        // '[name="password"]': "25592559",
        '[name="password"]': "56915691",
        // '[name="password_confirmation"]': "25592559",
        '[name="password_confirmation"]': "56915691",
        '[name="address"]': "123 Main St",
        '[name="affiliation"]': "SMKN 1 Cianjur",
        '[name="affiliation_address"]': "Jl. Siliwangi",
    };

    // Function to fill the form with default values
    function automateFillForm() {
        $.each(defaultValues, function (selector, value) {
            $(selector).val(value);
        });
    }

    // Automatically fill the form on page load
    automateFillForm();

    // Optionally, you can trigger filling the form with a button click
    $("#fillFormButton").on("click", function () {
        fillForm();
    });
});
