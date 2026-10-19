document.addEventListener("DOMContentLoaded", function () {
    const logoInput = document.getElementById("logo");
    const logoPreview = document.getElementById("logo-preview");

    if (!logoInput || !logoPreview) {
        return;
    }
    let previewUrl;
    logoInput.addEventListener("change", function () {
        const file = logoInput.files?.[0];
        if (!file) {
            return;
        }
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
        }
        previewUrl = URL.createObjectURL(file);
        logoPreview.src = previewUrl;
        logoPreview.classList.remove("d-none");
    });

});
