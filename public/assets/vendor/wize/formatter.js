const mustDigit = (element) => {
    element.value = element.value.replace(/[^0-9]/g, "");
};

$(document).ready(function () {
    $(".wzf-only-digit").on("input", function () {
        mustDigit(this);
    });
});

function daysDiff(targetDate, comparatedDate = null) {
    const target = new Date(targetDate);
    let cpDate;

    if (!comparatedDate) {
        cpDate = new Date();
    } else {
        cpDate = new Date(comparatedDate);
    }

    const difference = target.getTime() - cpDate.getTime();
    const daysDifference = Math.ceil(difference / (1000 * 3600 * 24));

    return daysDifference;
}

function slugify(str) {
    return str
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-");
}