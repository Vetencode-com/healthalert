<link rel="stylesheet" href="https://cdn.datatables.net/v/bs5/dt-2.1.3/b-3.1.1/r-3.0.2/sl-2.0.4/datatables.min.css">
<style>
/* Make the pagination buttons circular */
.dt-container .pagination .dt-paging-button button {
    border-radius: 50%;
    width: 35px; /* Adjust width and height as needed */
    height: 35px;
    text-align: center;
    padding: 0;
    margin: 0 2px; /* Adjust margin as needed */
    color: var(--bs-primary);
}

/* Active button customization */
.dt-container .pagination .dt-paging-button.active button {
    background-color: var(--bs-primary);
    color: white; /* Ensure text color is readable */
    border: none;
}
.dt-container .pagination .dt-paging-button.disabled button {
    color: var(--bs-gray-600);
}

/* Penyesuaian penggunaan vendor datatable yang baru */
.dataTables_wrapper .pagination .paginate_button a {
    border-radius: 50%;
    height: 35px; /* Fixed height */
    min-width: 35px; /* Ensure minimum width matches height for circular buttons */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 10px; /* Add padding to ensure enough space for larger numbers */
    margin: 0 2px;
    color: var(--bs-primary);
    text-align: center;
}

/* Active button customization */
.dataTables_wrapper .pagination .paginate_button.active a {
    background-color: var(--bs-primary);
    color: white; /* Ensure text color is readable */
    border: none;
}
.dataTables_wrapper .pagination .paginate_button.disabled a {
    color: var(--bs-gray-600);
}

</style>